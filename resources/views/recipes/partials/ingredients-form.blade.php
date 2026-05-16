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

<section class="recipe-ingredients-editor" data-ingredients-editor>
    <h2>Ingredientes</h2>

    <div class="ingredient-fields">
        <div>
            <label for="ingredient-name">Ingrediente:</label>
            <input
                type="text"
                id="ingredient-name"
                data-ingredient-name
                list="ingredient-suggestions"
                autocomplete="off"
                maxlength="30"
            >
            <datalist id="ingredient-suggestions" data-ingredient-suggestions></datalist>
        </div>

        <div>
            <label for="ingredient-quantity">Cantidad:</label>
            <input
                type="number"
                id="ingredient-quantity"
                data-ingredient-quantity
                step="0.01"
                min="0"
            >
        </div>

        <div>
            <label for="ingredient-unit">Unidad:</label>
            <input
                type="text"
                id="ingredient-unit"
                data-ingredient-unit
                maxlength="30"
                placeholder="g, ml, unidades..."
            >
        </div>

        <button type="button" data-add-ingredient>A&ntilde;adir ingrediente</button>
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

    function setFeedback(message, type = '') {
        feedback.textContent = message;
        feedback.dataset.type = type;
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
            removeButton.textContent = 'Quitar';

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
        suggestions.innerHTML = '';

        results.forEach(function (ingredient) {
            const option = document.createElement('option');
            option.value = ingredient.name;
            suggestions.appendChild(option);
        });
    }

    nameInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);

        const query = nameInput.value.trim();

        if (query.length < 2) {
            suggestions.innerHTML = '';
            return;
        }

        searchTimeout = setTimeout(function () {
            searchIngredients(query);
        }, 250);
    });

    addButton.addEventListener('click', async function () {
        const name = nameInput.value.trim();

        if (!name) {
            setFeedback('Escribe un ingrediente.', 'error');
            return;
        }

        addButton.disabled = true;
        setFeedback('Comprobando ingrediente...', 'loading');

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
                setFeedback((data.message || 'No se pudo anadir el ingrediente.') + reason, 'error');
                return;
            }

            const ingredient = data.ingredient;
            const alreadySelected = ingredients.some(function (item) {
                return Number(item.id) === Number(ingredient.id);
            });

            if (alreadySelected) {
                setFeedback('Este ingrediente ya esta en la receta.', 'error');
                return;
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
            suggestions.innerHTML = '';

            renderIngredients();
            setFeedback(data.message || 'Ingrediente anadido.', 'success');
        } catch (error) {
            setFeedback('No se pudo conectar con el servidor.', 'error');
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
