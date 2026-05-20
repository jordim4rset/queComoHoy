@php
    $selectedIngredients = isset($receta)
        ? $receta->ingredients->map(fn ($ingredient) => [
            'id' => $ingredient->id,
            'name' => $ingredient->name,
            'quantity' => $ingredient->pivot->quantity,
            'unit' => $ingredient->pivot->unit,
        ])->values()
        : collect();
@endphp

<section
    class="recipe-ingredients-editor"
    data-ingredients-editor
    data-already-selected="{{ __('messages.ingredient_already_selected') }}"
    data-existing-selected="{{ __('messages.ingredient_existing_selected') }}"
    data-write-ingredient="{{ __('messages.write_ingredient') }}"
    data-existing-added="{{ __('messages.ingredient_existing_added') }}"
    data-checking-ingredient="{{ __('messages.checking_ingredient') }}"
    data-ingredient-add-error="{{ __('messages.ingredient_add_error') }}"
    data-ingredient-added="{{ __('messages.ingredient_added') }}"
    data-server-connect-error="{{ __('messages.server_connect_error') }}"
>
    <h2>{{ __('messages.ingredients') }}</h2>

    <div class="ingredient-fields">
        <div>
            <label for="ingredient-name">{{ __('messages.ingredient') }}:</label>
            <div class="ingredient-search">
                <input
                    type="text"
                    id="ingredient-name"
                    data-ingredient-name
                    autocomplete="off"
                    maxlength="30"
                >
                <ul class="ingredient-suggestions" data-ingredient-suggestions hidden></ul>
            </div>
        </div>

        <div>
            <label for="ingredient-quantity">{{ __('messages.quantity') }}:</label>
            <input
                type="number"
                id="ingredient-quantity"
                data-ingredient-quantity
                step="0.01"
                min="0"
            >
        </div>

        <div>
            <label for="ingredient-unit">{{ __('messages.unit') }}:</label>
            <input
                type="text"
                id="ingredient-unit"
                data-ingredient-unit
                maxlength="30"
                placeholder="{{ __('messages.unit_placeholder') }}"
            >
        </div>

        <button type="button" data-add-ingredient>{{ __('messages.add_ingredient') }}</button>
    </div>

    <p class="ingredient-feedback" data-ingredient-feedback></p>

    <ul class="selected-ingredients" data-selected-ingredients></ul>
    <div data-ingredient-inputs></div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editor = document.querySelector('[data-ingredients-editor]');

    if (!editor) {
        return;
    }

    const nameInput = editor.querySelector('[data-ingredient-name]');
    const quantityInput = editor.querySelector('[data-ingredient-quantity]');
    const unitInput = editor.querySelector('[data-ingredient-unit]');
    const addButton = editor.querySelector('[data-add-ingredient]');
    const suggestions = editor.querySelector('[data-ingredient-suggestions]');
    const selectedList = editor.querySelector('[data-selected-ingredients]');
    const hiddenInputs = editor.querySelector('[data-ingredient-inputs]');
    const feedback = editor.querySelector('[data-ingredient-feedback]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let ingredients = @json($selectedIngredients);
    let searchTimeout = null;
    let suggestedIngredients = [];

    function normalizeIngredientName(value) {
        return value
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim()
            .replace(/\s+/g, ' ');
    }

    function setFeedback(message, type = '') {
        feedback.textContent = message;
        feedback.dataset.type = type;
    }

    function clearSuggestions() {
        suggestions.innerHTML = '';
        suggestions.hidden = true;
    }

    function renderSuggestions() {
        suggestions.innerHTML = '';

        if (!suggestedIngredients.length) {
            suggestions.hidden = true;
            return;
        }

        suggestedIngredients.forEach(function (ingredient) {
            const item = document.createElement('li');
            const button = document.createElement('button');

            button.type = 'button';
            button.dataset.ingredientId = ingredient.id;
            button.textContent = ingredient.name;

            item.appendChild(button);
            suggestions.appendChild(item);
        });

        suggestions.hidden = false;
    }

    function addIngredientToRecipe(ingredient, message) {
        const alreadySelected = ingredients.some(function (item) {
            return Number(item.id) === Number(ingredient.id);
        });

        if (alreadySelected) {
            setFeedback(editor.dataset.alreadySelected, 'error');
            return false;
        }

        ingredients.push({
            id: ingredient.id,
            name: ingredient.name,
            quantity: quantityInput.value,
            unit: unitInput.value.trim(),
        });

        nameInput.value = '';
        quantityInput.value = '';
        unitInput.value = '';
        clearSuggestions();
        suggestedIngredients = [];

        renderIngredients();
        setFeedback(message, 'success');

        return true;
    }

    function renderIngredients() {
        selectedList.innerHTML = '';
        hiddenInputs.innerHTML = '';

        ingredients.forEach(function (ingredient, index) {
            const item = document.createElement('li');
            item.className = 'selected-ingredient-item';

            const quantity = ingredient.quantity ? ingredient.quantity + ' ' : '';
            const unit = ingredient.unit ? ingredient.unit + ' ' : '';
            const label = document.createElement('span');
            const name = document.createElement('strong');
            const removeButton = document.createElement('button');

            name.textContent = ingredient.name;
            label.appendChild(name);
            label.appendChild(document.createTextNode(` ${quantity}${unit}`));

            removeButton.type = 'button';
            removeButton.className = 'btn-remove-ingredient';
            removeButton.dataset.index = index;
            removeButton.textContent = '{{ __('messages.remove') }}';

            item.appendChild(label);
            item.appendChild(removeButton);

            selectedList.appendChild(item);

            [
                ['id', ingredient.id],
                ['quantity', ingredient.quantity ?? ''],
                ['unit', ingredient.unit ?? ''],
            ].forEach(function ([field, value]) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `ingredients[${index}][${field}]`;
                input.value = value;
                hiddenInputs.appendChild(input);
            });
        });
    }

    async function searchIngredients(query) {
        const response = await fetch(`{{ route('ingredientes.searchForRecipe') }}?q=${encodeURIComponent(query)}`, {
            headers: {
                'Accept': 'application/json',
            },
        });

        if (!response.ok) {
            return;
        }

        const results = await response.json();
        suggestedIngredients = results;
        renderSuggestions();
    }

    nameInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);

        const query = nameInput.value.trim();

        if (query.length < 2) {
            clearSuggestions();
            suggestedIngredients = [];
            return;
        }

        searchTimeout = setTimeout(function () {
            searchIngredients(query);
        }, 250);
    });

    nameInput.addEventListener('focus', function () {
        renderSuggestions();
    });

    suggestions.addEventListener('click', function (event) {
        const button = event.target.closest('[data-ingredient-id]');

        if (!button) {
            return;
        }

        const ingredient = suggestedIngredients.find(function (item) {
            return Number(item.id) === Number(button.dataset.ingredientId);
        });

        if (!ingredient) {
            return;
        }

        nameInput.value = ingredient.name;
        clearSuggestions();
        setFeedback(editor.dataset.existingSelected, 'success');
    });

    document.addEventListener('click', function (event) {
        if (!editor.contains(event.target)) {
            clearSuggestions();
        }
    });

    addButton.addEventListener('click', async function () {
        const name = nameInput.value.trim();

        if (!name) {
            setFeedback(editor.dataset.writeIngredient, 'error');
            return;
        }

        const normalizedName = normalizeIngredientName(name);
        const existingIngredient = suggestedIngredients.find(function (ingredient) {
            return normalizeIngredientName(ingredient.name) === normalizedName
                || ingredient.normalized_name === normalizedName;
        });

        if (existingIngredient) {
            addIngredientToRecipe(existingIngredient, editor.dataset.existingAdded);
            return;
        }

        addButton.disabled = true;
        setFeedback(editor.dataset.checkingIngredient, 'loading');

        try {
            const response = await fetch(`{{ route('ingredientes.storeFromRecipe') }}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ name }),
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                const reason = data.reason ? ` ${data.reason}` : '';
                setFeedback((data.message || editor.dataset.ingredientAddError) + reason, 'error');
                return;
            }

            const ingredient = data.ingredient;
            addIngredientToRecipe(ingredient, data.message || editor.dataset.ingredientAdded);
        } catch (error) {
            setFeedback(editor.dataset.serverConnectError, 'error');
        } finally {
            addButton.disabled = false;
        }
    });

    selectedList.addEventListener('click', function (event) {
        if (!event.target.matches('[data-index]')) {
            return;
        }

        ingredients.splice(Number(event.target.dataset.index), 1);
        renderIngredients();
    });

    renderIngredients();
});
</script>
