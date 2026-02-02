#!/bin/bash

# Cleanup both processes on exit
cleanup() {
    kill $TAILWIND_PID $SCULPIN_PID 2>/dev/null
}
trap cleanup EXIT

# Run Tailwind with prefixed output
(npx tailwind -i assets/css/app.css -o source/assets/css/app.css --watch 2>&1 | awk '{print "[tailwind] " $0; fflush()}') &
TAILWIND_PID=$!

# Run Sculpin with prefixed output
(./vendor/bin/sculpin generate --watch --server 2>&1 | awk '{print "[sculpin] " $0; fflush()}') &
SCULPIN_PID=$!

# Wait for both
wait
