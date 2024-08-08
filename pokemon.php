<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pokémon</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <!-- Logo de Pokémon -->
        <header>
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/International_Pokémon_logo.svg/320px-International_Pokémon_logo.svg.png" alt="Pokémon Logo" class="pokemon-logo">
        </header>

        <!-- Título de la página -->
        <h1>Detalles del Pokémon</h1>

        <?php
        $pokemon_name = isset($_GET['name']) ? strtolower($_GET['name']) : '';

        if ($pokemon_name) {
            $response = @file_get_contents("https://pokeapi.co/api/v2/pokemon/{$pokemon_name}");
            if ($response !== FALSE) {
                $pokemon = json_decode($response, true);
                echo "<div class='pokemon-detail'>";
                echo "<h2>" . ucfirst($pokemon['name']) . "</h2>";
                echo "<img src='" . $pokemon['sprites']['front_default'] . "' alt='" . $pokemon['name'] . "' class='pokemon-detail-sprite'>";
                echo "<div class='medidas'>";
                echo "<p><strong>Altura:</strong> " . $pokemon['height'] / 10 . " m</p>";
                echo "<p><strong>Peso:</strong> " . $pokemon['weight'] / 10 . " kg</p>";
                echo "</div>";
                echo "<h3>Tipos:</h3>";
                echo "<div class='tipos'>";
                foreach ($pokemon['types'] as $type) {
                    echo "<p>" . ucfirst($type['type']['name']) . "</p>";
                }
                echo "</div>";
                echo "<h3>Habilidades:</h3>";
                echo "<div class='habilidades'>";
                foreach ($pokemon['abilities'] as $ability) {
                    echo "<p>" . ucfirst($ability['ability']['name']) . "</p>";
                }
                echo "</div>";
        ?>
                <a href="index.php" class="back-button" title="Volver">
                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="50" height="50" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left">
                        <g fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 18l-6-6 6-6" />
                        </g>
                        <circle cx="12" cy="12" r="11" stroke="#ffcb05" stroke-width="2" fill="none" />
                        <circle cx="12" cy="12" r="7" stroke="#ffcb05" stroke-width="2" fill="none" />
                    </svg>

                </a>
        <?php
                echo "</div>";
            } else {
                echo "<p>No se encontraron detalles para el Pokémon: " . htmlspecialchars($pokemon_name) . "</p>";
            }
        } else {
            echo "<p>No se ha especificado un Pokémon para mostrar.</p>";
        }
        ?>


    </div>
</body>

</html>