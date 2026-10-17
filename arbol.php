<?php
function construirArbol(array $recorridos): array
{
    $preorden = analizarRecorrido($recorridos['preorden'] ?? '');
    $inorden = analizarRecorrido($recorridos['inorden'] ?? '');
    $postorden = analizarRecorrido($recorridos['postorden'] ?? '');
    $disponibles = [];

    if ($preorden !== []) {
        $disponibles['preorden'] = $preorden;
    }
    if ($inorden !== []) {
        $disponibles['inorden'] = $inorden;
    }
    if ($postorden !== []) {
        $disponibles['postorden'] = $postorden;
    }

    if (count($disponibles) < 2) {
        throw new InvalidArgumentException('Ingresa al menos dos recorridos.');
    }

    if (isset($disponibles['preorden'], $disponibles['inorden'])) {
        $pareja = ['preorden', 'inorden'];
    } elseif (isset($disponibles['inorden'], $disponibles['postorden'])) {
        $pareja = ['inorden', 'postorden'];
    } else {
        $pareja = ['preorden', 'postorden'];
    }

    $primero = $disponibles[$pareja[0]];
    $segundo = $disponibles[$pareja[1]];
    validarRecorridos($primero, $segundo);

    if ($pareja === ['preorden', 'inorden']) {
        $arbol = desdePreordenEInorden($primero, $segundo);
    } elseif ($pareja === ['inorden', 'postorden']) {
        $arbol = desdeInordenYPostorden($primero, $segundo);
    } else {
        $arbol = desdePreordenYPostorden($primero, $segundo);
    }

    $generados = obtenerRecorridos($arbol);
    foreach ($disponibles as $nombre => $recorrido) {
        if ($generados[$nombre] !== $recorrido) {
            throw new InvalidArgumentException('Los recorridos ingresados no corresponden al mismo árbol.');
        }
    }

    return [
        'arbol' => $arbol,
        'recorridos' => $generados,
        'pareja' => $pareja
    ];
}

function analizarRecorrido(string $entrada): array
{
    $nodos = preg_split('/[\s,;→]+/u', trim($entrada), -1, PREG_SPLIT_NO_EMPTY);
    return $nodos ?: [];
}

function renderizarArbol(?array $nodo): string
{
    if ($nodo === null) {
        return '';
    }

    $html = '<li><span class="nodo-arbol">' .
        htmlspecialchars($nodo['valor'], ENT_QUOTES, 'UTF-8') .
        '</span>';
    if ($nodo['izquierdo'] !== null || $nodo['derecho'] !== null) {
        $html .= '<ul>';
        $html .= renderizarArbol($nodo['izquierdo']);
        $html .= renderizarArbol($nodo['derecho']);
        $html .= '</ul>';
    }

    return $html . '</li>';
}

function validarRecorridos(array $primero, array $segundo): void
{
    if (count($primero) !== count($segundo) || count(array_unique($primero)) !== count($primero) ||
        count(array_unique($segundo)) !== count($segundo) ||
        array_diff($primero, $segundo) !== [] || array_diff($segundo, $primero) !== []) {
        throw new InvalidArgumentException('Los recorridos deben tener los mismos nodos, sin valores repetidos.');
    }
}

function desdePreordenEInorden(array $preorden, array $inorden): ?array
{
    if ($preorden === []) {
        return null;
    }

    $raiz = $preorden[0];
    $posicion = array_search($raiz, $inorden, true);
    if ($posicion === false) {
        throw new InvalidArgumentException('Los recorridos no son compatibles.');
    }

    $cantidadIzquierda = $posicion;
    return [
        'valor' => $raiz,
        'izquierdo' => desdePreordenEInorden(
            array_slice($preorden, 1, $cantidadIzquierda),
            array_slice($inorden, 0, $cantidadIzquierda)
        ),
        'derecho' => desdePreordenEInorden(
            array_slice($preorden, $cantidadIzquierda + 1),
            array_slice($inorden, $cantidadIzquierda + 1)
        )
    ];
}

function desdeInordenYPostorden(array $inorden, array $postorden): ?array
{
    if ($postorden === []) {
        return null;
    }

    $raiz = $postorden[count($postorden) - 1];
    $posicion = array_search($raiz, $inorden, true);
    if ($posicion === false) {
        throw new InvalidArgumentException('Los recorridos no son compatibles.');
    }

    $cantidadIzquierda = $posicion;
    return [
        'valor' => $raiz,
        'izquierdo' => desdeInordenYPostorden(
            array_slice($inorden, 0, $cantidadIzquierda),
            array_slice($postorden, 0, $cantidadIzquierda)
        ),
        'derecho' => desdeInordenYPostorden(
            array_slice($inorden, $cantidadIzquierda + 1),
            array_slice($postorden, $cantidadIzquierda, count($postorden) - $cantidadIzquierda - 1)
        )
    ];
}

function desdePreordenYPostorden(array $preorden, array $postorden): ?array
{
    if ($preorden === []) {
        return null;
    }

    $ultimo = count($preorden) - 1;
    if ($preorden[0] !== $postorden[$ultimo]) {
        throw new InvalidArgumentException('Los recorridos no son compatibles.');
    }

    if ($ultimo === 0) {
        return ['valor' => $preorden[0], 'izquierdo' => null, 'derecho' => null];
    }

    $raizIzquierda = array_search($preorden[1], $postorden, true);
    if ($raizIzquierda === false || $raizIzquierda >= $ultimo) {
        throw new InvalidArgumentException('Los recorridos no son compatibles.');
    }

    $cantidadIzquierda = $raizIzquierda + 1;
    return [
        'valor' => $preorden[0],
        'izquierdo' => desdePreordenYPostorden(
            array_slice($preorden, 1, $cantidadIzquierda),
            array_slice($postorden, 0, $cantidadIzquierda)
        ),
        'derecho' => desdePreordenYPostorden(
            array_slice($preorden, $cantidadIzquierda + 1),
            array_slice($postorden, $cantidadIzquierda, $ultimo - $cantidadIzquierda)
        )
    ];
}

function obtenerRecorridos(?array $nodo): array
{
    if ($nodo === null) {
        return ['preorden' => [], 'inorden' => [], 'postorden' => []];
    }

    $izquierdo = obtenerRecorridos($nodo['izquierdo']);
    $derecho = obtenerRecorridos($nodo['derecho']);
    return [
        'preorden' => array_merge([$nodo['valor']], $izquierdo['preorden'], $derecho['preorden']),
        'inorden' => array_merge($izquierdo['inorden'], [$nodo['valor']], $derecho['inorden']),
        'postorden' => array_merge($izquierdo['postorden'], $derecho['postorden'], [$nodo['valor']])
    ];
}

$entradas = [
    'preorden' => $_POST['preorden'] ?? '',
    'inorden' => $_POST['inorden'] ?? '',
    'postorden' => $_POST['postorden'] ?? ''
];
$construccion = null;
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $construccion = construirArbol($entradas);
    } catch (InvalidArgumentException $error) {
        $mensaje = $error->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árbol binario | Taller PHP</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/actividades.css">
</head>
<body class="actividad-pagina actividad-arbol">
    <main class="actividad-contenido">
        <p class="actividad-etiqueta">Actividad 6</p>
        <h1>Construcción de árbol binario</h1>
        <p class="actividad-descripcion">Ingresa al menos dos recorridos, separados por espacios, comas o flechas. Cada nodo debe tener un valor único.</p>

        <form method="POST" class="acronimo-formulario">
            <label for="preorden">Preorden</label>
            <textarea id="preorden" name="preorden" rows="2" placeholder="A → B → D → E → C"><?php echo htmlspecialchars($entradas['preorden'], ENT_QUOTES, 'UTF-8'); ?></textarea>

            <label for="inorden">Inorden</label>
            <textarea id="inorden" name="inorden" rows="2" placeholder="D → B → E → A → C"><?php echo htmlspecialchars($entradas['inorden'], ENT_QUOTES, 'UTF-8'); ?></textarea>

            <label for="postorden">Postorden</label>
            <textarea id="postorden" name="postorden" rows="2" placeholder="D → E → B → C → A"><?php echo htmlspecialchars($entradas['postorden'], ENT_QUOTES, 'UTF-8'); ?></textarea>

            <p class="actividad-ayuda">Si ingresas los tres, se construye usando preorden e inorden y se valida el postorden.</p>
            <button class="acronimo-convertir" type="submit">Construir árbol</button>
        </form>

        <section class="acronimo-resultado" aria-live="polite" aria-atomic="true">
            <h2>Resultado</h2>
            <?php
            if ($mensaje !== '') {
                echo '<p class="actividad-error">' . htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</p>';
            } elseif ($construccion !== null) {
                echo '<p class="actividad-ayuda">Construido con ' .
                    htmlspecialchars(implode(' e ', $construccion['pareja']), ENT_QUOTES, 'UTF-8') . '.</p>';
                echo '<div class="arbol-salida"><ul>' . renderizarArbol($construccion['arbol']) . '</ul></div>';
                echo '<div class="recorridos-salida">';
                echo '<p><strong>Preorden:</strong> ' .
                    htmlspecialchars(implode(' → ', $construccion['recorridos']['preorden']), ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p><strong>Inorden:</strong> ' .
                    htmlspecialchars(implode(' → ', $construccion['recorridos']['inorden']), ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p><strong>Postorden:</strong> ' .
                    htmlspecialchars(implode(' → ', $construccion['recorridos']['postorden']), ENT_QUOTES, 'UTF-8') . '</p>';
                echo '</div>';
            } else {
                echo '<output>El árbol y sus recorridos aparecerán aquí.</output>';
            }
            ?>
        </section>

        <a class="actividad-volver" href="index.html#arbol">Volver al menú</a>
    </main>
</body>
</html>
