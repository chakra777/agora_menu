# Imágenes de productos — Menú AGORA

## Cómo agregar una foto

1. Guarda la imagen en `assets/productos/` con el nombre **`{ID_PROD}.webp`** (también acepta `.jpg`, `.jpeg`, `.png`).
   - Ejemplo: hamburguesa con ID `601` → `assets/productos/601.webp`

2. O asigna una ruta local en la base de datos:
   ```sql
   UPDATE productos SET imagen_url = 'assets/productos/601.webp' WHERE ID_PROD = 601;
   ```

3. O una URL pública de la web (debe incluir `http://` o `https://`):
   ```sql
   UPDATE productos SET imagen_url = 'https://ejemplo.com/fotos/601.jpg' WHERE ID_PROD = 601;
   ```

## Prioridad de resolución

1. `imagen_url` en la BD: URL `http(s)://...` tal cual, o ruta local si el archivo existe
2. `assets/productos/{ID_PROD}.webp|jpg|jpeg|png`
3. Placeholder por categoría (`assets/placeholders/cocina.svg`, etc.)
4. `assets/placeholders/default.svg`

## Recomendaciones

- Tamaño sugerido: 800×600 px, formato WebP o JPEG.
- Peso objetivo: menos de 150 KB por imagen.
