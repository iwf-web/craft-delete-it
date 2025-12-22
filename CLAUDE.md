# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is **Delete It!**, a Craft CMS 5 plugin that provides a control panel utility for bulk-deleting entries and Craft Commerce products. It's intended for development/local environments only.

## Common Commands

```bash
# Check code style (ECS with Craft CMS coding standards)
composer check-cs

# Fix code style issues
composer fix-cs

# Run static analysis (PHPStan level 9)
composer phpstan
```

## Architecture

The plugin follows standard Craft CMS 5 plugin architecture:

- **`src/DeleteIt.php`** - Main plugin class, registers the utility on CP requests
- **`src/utilities/DeleteItUtility.php`** - Craft Utility that renders the UI, lists sections and Commerce product types
- **`src/controllers/DeleteItController.php`** - Handles the `delete` action, performs hard deletion of entries/products
- **`src/templates/_utilities/delete-it.twig`** - Twig template for the utility UI

## Key Technical Details

- Uses Craft's Utility system (appears under Utilities in the CP)
- Hard-deletes elements via `Craft::$app->getElements()->deleteElementById($id, hardDelete: true)`
- Commerce integration is optional - checks `Craft::$app->plugins->isPluginInstalled('commerce')` before loading product types
- PSR-4 autoloading: `iwf\craftdeleteit\` maps to `src/`

## Git Workflow

- Base new feature branches off `develop`
- Main branch is `main`
