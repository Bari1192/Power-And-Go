#!/bin/bash

if ! [ -d "frontend/node_modules" ]; then
    docker run --rm  -v "$(pwd)/frontend:/app" --entrypoint npm idomi27/vue install
fi

docker compose up -d
