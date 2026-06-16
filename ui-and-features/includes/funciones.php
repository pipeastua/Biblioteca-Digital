<?php
// Constantes de validacion
define('MAX_ISBN',    13);
define('MIN_AUTOR',    3);
define('MIN_PAGES',  1);
define('MAX_PAGES', 5000);
define('MIN_AMOUNT', 1);
define('MIN_YEAR',  1900);
define('MAX_YEAR',  (int)date('Y'));

function iniciarSesionApp(): void
{
    $duration = 604800;
    ini_set('session.gc_maxlifetime', (string)$duration);
    session_set_cookie_params([
        'lifetime' => $duration,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function inicializarLibros(): void
{
    if (!isset($_SESSION['books']) || !is_array($_SESSION['books'])) {
        $_SESSION['books'] = bookStock();
    }
}

// Generos
function getGenres(): array
{
    return ['Fantasia', 'Sci-Fi', 'Terror', 'Suspenso', 'Romance', 'Realismo Magico', 'Historia'];
}

// Sanitizar
function sanitizar(string $data): string
{
    return htmlspecialchars(trim($data), ENT_QUOTES);
}

// Verfificacion existente del ISBN
function isbnExist(string $isbn, array $books): bool
{
    foreach ($books as $book) {
        if ($book['isbn'] === $isbn) {
            return true;
        }
    }
    return false;
}

// Stock
function bookStock(): array
{
    return [
        [
            'isbn' => '1234567890',
            'title' => 'El Principito',
            'autor' => 'Antoine de Saint-Exupéry',
            'pages' => 100,
            'amount' => 10,
            'year' => 2024,
            'genre' => 'Fantasia',
            'stock' => 10,
            'available' => true
        ],
        [
            'isbn' => '9788466331869',
            'title' => '1984',
            'autor' => 'George Orwell',
            'pages' => 328,
            'amount' => 15,
            'year' => 2021,
            'genre' => 'Sci-Fi',
            'stock' => 15,
            'available' => true
        ],
        [
            'isbn' => '9788439733478',
            'title' => 'Cien años de soledad',
            'autor' => 'Gabriel García Márquez',
            'pages' => 496,
            'amount' => 8,
            'year' => 2019,
            'genre' => 'Realismo Magico',
            'stock' => 8,
            'available' => true
        ],
    ];
}

// Stats para cada libro
function stats(array $books): array
{
    $total = count($books);
    $availables = 0;
    $notAvailables = 0;
    $totalInventory = 0;
    $minYear = null;
    $maxYear = null;
    $oldestBook = 0;
    $newestBook = 0;
    $genres = [];

    if ($total === 0) {
        return [
            'total' => 0,
            'availables' => 0,
            'notAvailable' => 0,
            'totalInventory' => 0,
            'oldestBook' => 'N/A',
            'newestBook' => 'N/A',
            'minYear' => 'N/A',
            'maxYear' => 'N/A',
            'popularGenre' => 'N/A',
            'genres' => []
        ];
    }

    foreach ($books as $book) {
        $book['available'] ? $availables++ : $notAvailables++;

        // Total en el inventario
        $totalInventory += $book['stock'];

        // book$book mas antiguo
        if ($minYear === null || $book['year'] < $minYear) {
            $minYear = $book['year'];
            $oldestBook = $book['title'];
        }

        // book$book mas nuevo
        if ($maxYear === null || $book['year'] > $maxYear) {
            $maxYear = $book['year'];
            $newestBook = $book['title'];
        }

        // Contar generos
        if (!isset($genres[$book['genre']])) {
            $genres[$book['genre']] = 0;
        }
        $genres[$book['genre']]++;
    }

    // Genero popular
    $popularGenre = 'N/A';
    $maxCount = 0;
    foreach ($genres as $genre => $count) {
        if ($count > $maxCount) {
            $maxCount = $count;
            $popularGenre = $genre;
        }
    }

    return [
        'total' => $total,
        'availables' => $availables,
        'notAvailables' => $notAvailables,
        'totalInventory' => $totalInventory,
        'oldestBook' => $oldestBook,
        'newestBook' => $newestBook,
        'minYear' => $minYear,
        'maxYear' => $maxYear,
        'popularGenre' => $popularGenre,
        'genres' => $genres
    ];
}

// Filtrado de busqueda
function bookFinder(
    array $books,
    string $title = '',
    string $genre = '',
    string $availability = 'all'
): array {
    $results = [];

    foreach ($books as $book) {
        $coincide = true;

        // Filtrado por titulo
        if (!empty(trim($title))) {
            if (stripos($book['title'], trim($title)) === false) {
                $coincide = false;
            }
        }

        // Filtrado por genero
        if (!empty($genre) && $genre !== 'all') {
            if ($book['genre'] !== $genre) {
                $coincide = false;
            }
        }

        // Filtrado por disponibilidad
        switch ($availability) {
            case 'availables':
                if (!$book['available']) {
                    $coincide = false;
                }
                break;

            case 'notAvailables':
                if ($book['available']) {
                    $coincide = false;
                }
                break;

            default:
                break;
        }

        if ($coincide) {
            array_push($results, $book);
        }
    }

    return $results;
}
