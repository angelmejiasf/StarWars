<!DOCTYPE html>
<html>
    <head>
        <title>Star Wars Characters</title>
        <!-- Incluir los archivos CSS y JS de Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container mt-4">
            <h1>Star Wars Characters</h1>
            <form method="post">
                <div class="form-group">
                    <label for="characterSelect">Selecciona un personaje:</label>
                    <select class="form-control" id="characterSelect" name="character">
                        <?php
                        $selected_character = isset($_POST['character']) ? $_POST['character'] : '';

                        // Hacer la solicitud para obtener la lista de personajes
                        $api_url = "https://swapi.dev/api/people";
                        $response = file_get_contents($api_url);
                        $data = json_decode($response, true);

                        if ($data && isset($data['results'])) {
                            $characters = $data['results'];

                            foreach ($characters as $index => $cha) {
                                $selected = ($selected_character == ($index + 1)) ? 'selected' : '';
                                echo '<option value="' . ($index + 1) . '"' . $selected . '>' . $cha['name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No se pueden obtener los personajes</option>';
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="buscar">Ver detalles</button>
            </form>

            <?php
            if (isset($_POST['buscar']) && isset($_POST['character'])) {
                $selected_index = intval($_POST['character']);

                // Hacer la solicitud específica para el personaje seleccionado
                $api_url = "https://swapi.dev/api/people/" . $selected_index . "/";
                $response = file_get_contents($api_url);
                $selected_character_data = json_decode($response, true);

                if ($selected_character_data) {
                    echo '<h2>Detalles del Personaje</h2>';
                    echo '<p><strong>Nombre:</strong> ' . $selected_character_data['name'] . '</p>';
                    echo '<p><strong>Altura:</strong> ' . $selected_character_data['height'] . ' cm</p>';
                    echo '<p><strong>Peso:</strong> ' . $selected_character_data['mass'] . ' kg</p>';
                    echo '<p><strong>Color de Pelo:</strong> ' . $selected_character_data['hair_color'] . '</p>';
                    echo '<p><strong>Color de Ojos:</strong> ' . $selected_character_data['eye_color'] . '</p>';

                    if (!empty($selected_character_data['films'])) {
                        echo '<ul>';
                        foreach ($selected_character_data['films'] as $film_url) {
                            $film_response = file_get_contents($film_url);
                            $film_data = json_decode($film_response, true);
                            if ($film_data) {
                                echo '<li><strong>Título:</strong> ' . $film_data['title'] . '<br>';
                                echo '<strong>Fecha de Lanzamiento:</strong> ' . $film_data['release_date'] . '</li>';
                            }
                        }
                        echo '</ul>';
                    }
                } else {
                    echo '<p>No se pueden obtener los datos del personaje seleccionado.</p>';
                }
            }
            ?>

        </div>
    </body>
</html>