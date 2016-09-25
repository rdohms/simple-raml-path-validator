# RAML Simple path validator

This is a simple approach to validating that a given url is defined in a RAML Spec file.
It only validates presence of path and method in the file, not response or payload details.

Its original goal was for use in validating that no new paths are introduced without revisiting the RAML spec.
