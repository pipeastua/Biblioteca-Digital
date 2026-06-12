<?php
// Constantes de validacion
define('MAX_ISBN',    13);
define('MIN_TITULO',   5);
define('MIN_AUTOR',    3);
define('MIN_PAGINAS',  1);
define('MAX_PAGINAS', 5000);
define('MIN_CANTIDAD', 1);
define('ANHO_MIN',  1900);
define('ANHO_MAX',  2024);

// Generos
function obtenerGeneros(): array {
    return ['Fantasia', 'Sci-Fi', 'Terror', 'Suspenso', 'Romance', 'Realismo Magico', 'Historia'];
}

// Sanitizar
function sanitizar(string $dato): string {
    return htmlspecialchars(trim($dato), ENT_QUOTES);
}

// Verfificacion existente del ISBN
function isbnExist(string $isbn, array $libros): bool {
    foreach ($libros as $libro) {
        if ($libro['isbn'] === $isbn) {
            return true;
        }
    }
    return false;
}

// Stock
function stockLibros(): array {
    return [
        [
        'isbn' => '1234567890',
        'titulo' => 'El Principito',
        'autor' => 'Antoine de Saint-Exupéry',
        'paginas' => 100,
        'cantidad' => 10,
        'anho' => 2024,
        'genero' => 'Fantasia',
        'stock' => 10,
        ],
        [
        'isbn' => '9788466331869',
        'titulo' => '1984',
        'autor' => 'George Orwell',
        'paginas' => 328,
        'cantidad' => 15,
        'anho' => 2021,
        'genero' => 'Sci-Fi',
        'stock' => 15,
        ],
        [
        'isbn' => '9788439733478',
        'titulo' => 'Cien años de soledad',
        'autor' => 'Gabriel García Márquez',
        'paginas' => 496,
        'cantidad' => 8,
        'anho' => 2019,
        'genero' => 'Realismo Magico',
        'stock' => 8,
        ],
    ];
}

// Stats para cada libro
function stats(array $libros): array {
    $total = count($libros);
    $disponibles = 0;
    $noDisponibles = 0;
    $totalPaginas = 0;
    $inventario = 0;
    $anhoMin = 0;
    $anhoMax = 0;
    $libroMasAntiguo = 0;
    $nuevo = 0;
    $generos = [];

    if ($total === 0) {
        return [
            'total' => 0,
            'disponibles' => 0,
            'noDisponibles' => 0,
            'totalPaginas' => 0,
            'promedioPaginas' => 0,
            'inventarioTotal' => 0,
            'libroMasAntiguo' => 'N/A',
            'libroMasNuevo' => 'N/A',
            'anhoMin' => 'N/A',
            'anhoMax' => 'N/A',
            'generoPopular' => 'N/A',
            'generos' => []
        ];
    }

    foreach ($libros as $libro) {
        $libro['disponible'] ? $disponibles++ : $noDisponibles++;

        // Total de paginas/inventario
        $totalPaginas += $libro['paginas'];
        $inventarioTotal += $libro['stock'];
        
        // Libro mas antiguo
        if ($anhoMin === null || $libro['anho'] < $anhoMin) {
            $anhoMin = $libro['anho'];
            $libroMasAntiguo = $libro['titulo'];
        }
    
        // Libro mas nuevo
        if ($anhoMax === null || $libro['anho'] > $anhoMax) {
            $anhoMax = $libro['anho'];
            $libroMasNuevo = $libro['titulo'];
        }
        
        // Contar generos
        if (!isset($generos[$libro['genero']])) {
            $generos[$libro['genero']] = 0;
        }
        $generos[$libro['genero']]++;
    }

    // Genero popular
    $generoPopular = 'N/A';
    $maxCount = 0;
    foreach ($generos as $genre => $count) {
        if ($count > $maxCount) {
            $maxCount = $count;
            $generoPopular = $genre;
        }
    }
    
    return [
        'total' => $total,
        'disponibles' => $disponibles,
        'noDisponibles' => $noDisponibles,
        'totalPaginas' => $totalPaginas,
        'inventarioTotal' => $inventario,
        'libroMasAntiguo' => $libroMasAntiguo,
        'libroMasNuevo' => $libroMasNuevo,
        'anhoMin' => $anhoMin,
        'anhoMax' => $anhoMax,
        'generoPopular' => $generoPopular,
        'generos' => $generos
    ];
}

// Filtrado de busqueda
function buscarLibros(
    array $libros,
    string $titulo ='',
    string $genero = '',
    string $disponibilidad = 'Todos'
): array {
    $resultados = [];
    
    foreach ($libros as $libro) {
        $coincide = true;

        // Filtrado por titulo
        if (!empty(trim($titulo))) {
            if (stripos($libro['titulo'], trim($titulo)) == false) {
                $coincide = false;
            }
        }
        
        // Filtrado por genero
        if (!empty($genero) && $genero !== 'todos') {
            if ($libro['genero'] !== $genero) {
                $coincide = false;
            }
        }

        // Filtrado por disponibilidad
        switch ($disponibilidad) {
            case 'disponibles':
                if (!$libro['disponible']) { $coincide = false; }
                break;

            case 'noDisponibles':
                if ($libro['disponible']) { $coincide = false; }
                break;

            default:
                break;
        }

        if ($coincide) {
            array_push($resultados, $libro);
        }
    }
    
    return $resultados;
} 
?>