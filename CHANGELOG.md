#ldl-container-builder CHANGELOG.md

All changes to this project are documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [vx.x.x] - xxxx-xx-xx

### Added

- feature/1202261016940094 - Add eval dump format
- feature/1201970485656753 - Array of dump options needs to be converted to a class
- feature/1201948369036949 - Return real path on options directories
- feature/1201930284829936 - Add write to different option classes
- feature/1201509919633570 - Partial rewrite of compiler / finder logic

### Changed

- fix/1202381973080659 - Compiler pass compiler compiles container when it shouldn't
- fix/1202252911447396 - Compiler pass file finder is not using default file pattern
- fix/1202252835115521 - Compiler pass validator throws incorrect exception
- fix/1202230803368576 - Fix service file finder excluded directories
- fix/1202230300055795 - Fix Service/Cpass FinderOptions empty constructor
- fix/1202216904076371 - Service file finder options can not be constructed from instances
- fix/1201941481429272 - Return types in file finders
- fix/1201941481429268 - Add correct interfaces to Options classes for JSON encoding
- fix/1201934743393893 - Fix composer binary
- fix/1201610905745301 - Fix examples

