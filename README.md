# Schema Generator

This library is used to generate the [schema.org](https://schema.org/) models
and view helpers for the TYPO3 extensions

- [schema](https://github.com/brotkrueml/schema)
- [schema_auto](https://github.com/brotkrueml/schema-auto)
- [schema_bib](https://github.com/brotkrueml/schema-bib)
- [schema_health](https://github.com/brotkrueml/schema-health)
- [schema_pending](https://github.com/brotkrueml/schema-pending)

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
