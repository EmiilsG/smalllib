#!/bin/bash
# Izmanto TESTA datubāzi, NEVIS galveno database.sqlite
# Šo skriptu izmantot, kad vajag atiestatīt DB migrācijas/testus
export DB_DATABASE=database/testing.sqlite
exec php artisan "$@"
