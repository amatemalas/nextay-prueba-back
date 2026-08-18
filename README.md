# Nextay — Prueba Técnica Backend

## Arranque

```bash
composer install
php artisan migrate
php artisan db:seed
```

## Decisiones

Usé `withCount` y `withAvg` de Eloquent para el endpoint de summary en vez de un query raw — resuelve todo con una sola query y queda más legible. Para el de latest-rates, hago un `with` con un closure que filtra por `valid_from` descendente y limita a 1. Probé con `latest()` primero pero no hace lo que uno espera: trae el más reciente global, no por grupo.

Los tres API Resources (`RoomTypeSummaryResource`, `RoomTypeLatestRateResource`, `LatestRateResource`) están separados para que si cambia el formato de respuesta, se toca un solo archivo.

El seeder tira 8 tipos de habitación con 12 tarifas cada uno, datos deterministas para que los tests sean predecibles. No usé factories ahí.

## Qué me costó

La query de latest-rates me complicó más de lo que esperaba. `latest()` en el `with` no funciona como esperas y me costó un rato darme cuenta. La solución final (closure + `limit(1)`) funciona bien pero tuve que testear bastante con fechas duplicadas para asegurarme.

## Qué dejé sin hacer

- Autenticación — los endpoints son públicos
- Paginación — devuelven todo de una
