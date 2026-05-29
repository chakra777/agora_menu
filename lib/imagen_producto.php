<?php

/**
 * Resuelve la URL pública de la imagen de un producto.
 */
function resolverImagenProducto(string $idProd, ?string $imagenUrl, string $categoria): string
{
    $raiz = dirname(__DIR__);

    if (!empty($imagenUrl)) {
        $imagenUrl = trim($imagenUrl);
        if (preg_match('#^https?://#i', $imagenUrl)) {
            return $imagenUrl;
        }
        $ruta = $raiz . '/' . ltrim(str_replace(['\\', '..'], ['/', ''], $imagenUrl), '/');
        if (is_file($ruta)) {
            return ltrim($imagenUrl, '/');
        }
    }

    $extensiones = ['webp', 'jpg', 'jpeg', 'png'];
    foreach ($extensiones as $ext) {
        $relativa = "assets/productos/{$idProd}.{$ext}";
        if (is_file($raiz . '/' . $relativa)) {
            return $relativa;
        }
    }

    $porCategoria = [
        'Cocina' => 'assets/placeholders/cocina.svg',
        'Cafe' => 'assets/placeholders/cafe.svg',
        'Bebida' => 'assets/placeholders/bebida.svg',
        'Mostrador' => 'assets/placeholders/mostrador.svg',
        'Barra' => 'assets/placeholders/barra.svg',
    ];

    $placeholder = $porCategoria[$categoria] ?? 'assets/placeholders/default.svg';
    if (!is_file($raiz . '/' . $placeholder)) {
        return 'assets/placeholders/default.svg';
    }

    return $placeholder;
}
