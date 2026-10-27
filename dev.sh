#!/usr/bin/env bash
# Local WordPress preview of the erawat-enterprise theme (WordPress Playground).
# Serves http://127.0.0.1:9400 with the theme folder mounted live: edit a file, reload the browser.
# The site is recreated on every start (pages are re-created by the theme on activation).
set -euo pipefail
cd "$(dirname "${BASH_SOURCE[0]}")"

# Silence npm engine-version warnings from the Playground CLI's dependencies.
export npm_config_loglevel=error

exec npx --yes @wp-playground/cli@3.1.61 server \
  --mount="$PWD/erawat-enterprise:/wordpress/wp-content/themes/erawat-enterprise" \
  --blueprint="$PWD/blueprint.json" \
  --port=9400 \
  --login
