# Schema Generator

This library is used to generate the [schema.org](https://schema.org/) models
and view helpers for the TYPO3 extensions

- [schema](https://github.com/brotkrueml/schema)
- schema_auto
- schema_bib
- schema_health
- schema_pending

## Usage

    bin/console schema:generate <extension> <path>

where `<extension>` can be one of
- core
- auto
- bib
- health
- pending

The `<path>` is the root path to the TYPO3 extension.

To generate the files for the core where the schema extension is
installed on the same level as this library use:

    bin/console schema:generate core ../schema
