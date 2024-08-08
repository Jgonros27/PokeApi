<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Pokémon</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Logo de Pokémon -->
        <header>
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/International_Pokémon_logo.svg/320px-International_Pokémon_logo.svg.png" alt="Pokémon Logo" class="pokemon-logo">
        </header>

        <!-- Título de la página -->
        <h1>Búsqueda de Pokémon</h1>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="index.php" class="search-form">
            <!-- Buscar por nombre -->
            <input type="text" name="name" placeholder="Buscar por nombre" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>" class="search-input">

            <!-- Filtro por tipo -->
            <label for="type">Tipo:</label>
            <select name="type" id="type" class="search-select">
                <option value="">Todos los tipos</option>
                <?php
                $types = json_decode(file_get_contents('https://pokeapi.co/api/v2/type'), true);
                foreach ($types['results'] as $type) {
                    $selected = isset($_GET['type']) && $_GET['type'] == $type['name'] ? 'selected' : '';
                    echo "<option value='{$type['name']}' {$selected}>" . ucfirst($type['name']) . "</option>";
                }
                ?>
            </select>

            <!-- Filtro por generación -->
            <label for="generation">Generación:</label>
            <select name="generation" id="generation" class="search-select">
                <option value="">Todas las generaciones</option>
                <?php
                $generations = json_decode(file_get_contents('https://pokeapi.co/api/v2/generation'), true);
                foreach ($generations['results'] as $index => $generation) {
                    $gen_number = $index + 1;
                    $selected = isset($_GET['generation']) && $_GET['generation'] == $gen_number ? 'selected' : '';
                    echo "<option value='{$gen_number}' {$selected}>Generación {$gen_number}</option>";
                }
                ?>
            </select>

            <button type="submit" class="search-button">Buscar</button>
            <a href="index.php" class="clear-button">Limpiar búsqueda</a>
        </form>

        <!-- Resultados -->
        <div id="results" class="results-container">
            <?php
            $name = isset($_GET['name']) ? strtolower($_GET['name']) : '';
            $type = isset($_GET['type']) ? $_GET['type'] : '';
            $generation = isset($_GET['generation']) ? $_GET['generation'] : '';

            $pokemons = [];

            // Filtrar por nombre
            if (!empty($name)) {
                $response = @file_get_contents("https://pokeapi.co/api/v2/pokemon/{$name}");
                if ($response !== FALSE) {
                    $pokemon = json_decode($response, true);
                    $pokemons[] = [
                        'name' => $pokemon['name'],
                        'sprite' => $pokemon['sprites']['front_default']
                    ];
                } else {
                    echo "<p>No se encontraron Pokémon con el nombre: " . htmlspecialchars($name) . "</p>";
                }
            } else {
                // Filtrar por tipo
                if (!empty($type)) {
                    $response = file_get_contents("https://pokeapi.co/api/v2/type/{$type}");
                    $data = json_decode($response, true);
                    foreach ($data['pokemon'] as $pokemon) {
                        $pokemons[] = [
                            'name' => $pokemon['pokemon']['name']
                        ];
                    }
                }

                // Filtrar por generación
                if (!empty($generation)) {
                    $response = file_get_contents("https://pokeapi.co/api/v2/generation/{$generation}");
                    $data = json_decode($response, true);
                    $filtered_pokemons = [];
                    foreach ($data['pokemon_species'] as $pokemon) {
                        $filtered_pokemons[] = $pokemon['name'];
                    }

                    // Si hay filtro por tipo, cruzar los resultados
                    if (!empty($type)) {
                        $pokemons = array_filter($pokemons, function($poke) use ($filtered_pokemons) {
                            return in_array($poke['name'], $filtered_pokemons);
                        });
                    } else {
                        foreach ($filtered_pokemons as $pokemon) {
                            $pokemons[] = [
                                'name' => $pokemon
                            ];
                        }
                    }
                }
            }

            // Mostrar resultados
            if (!empty($pokemons)) {
                echo "<h2>Resultados de la búsqueda:</h2>";
                foreach ($pokemons as $pokemon) {
                    $poke_name = ucfirst($pokemon['name']);
                    $poke_name_lower = strtolower($pokemon['name']);
                    echo "<a href='pokemon.php?name={$poke_name_lower}' class='pokemon-link' title='Ver más'>";
                    echo "<div class='pokemon-card'>";
                    echo "<p class='pokemon-name'>{$poke_name}</p>";
                    echo "</div></a>";
                }
            } elseif (empty($name)) {
                echo "<p>No se encontraron Pokémon con los filtros aplicados.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
