# Free Edition Publication Implementation Plan

> **For agentic workers:** Use superpowers:executing-plans to implement this plan task-by-task. Track completed steps below.

**Goal:** Prepare the supplied free edition for version checks, CI, and clean release ZIPs.

**Architecture:** Keep `dsr/modernadminpanel/` as the installable runtime. Use phpBB's native version feed and dependency-free checks. Package tracked runtime files with `git archive`; never compile or import private authoring sources.

**Tech Stack:** PHP, Node.js built-ins, Git, GitHub Actions.

**Spec:** User instructions in this session: unify supplied documentation first, then adapt the OAuth repository's update checks, CI, export attributes, and packaging with atomic commits.

## Global Constraints

- Keep extension ID `dsr/modernadminpanel` and version `2.0.0`.
- Keep root README and docs; import no SCSS or JavaScript authoring project.
- Do not change runtime behavior.
- Commit each logical deliverable separately.
- Do not claim remote checks passed without observing them.

## Review Focus

- Feed version and ZIP filename must match Composer metadata.
- Release ZIP must contain only `dsr/modernadminpanel/` files, including both runtime licences.
- Dirty or incorrectly tagged releases must fail rather than publish mixed files.
- Invalid settings and missing permissions must remain covered by helper tests.
- Missing bundled assets and broken local documentation links must fail checks.

## Tasks

### 1. Native version checks
- [x] Validate Composer metadata and feed version/URLs over HTTPS.
- [x] Commit metadata, feed, and update instructions together (`abafb28`).

### 2. Runtime and repository checks
- [x] Add PHP helper tests for option validation, palette permissions, and plain-text sanitation.
- [x] Add Node checks for metadata, asset references, language parity, and local Markdown links.
- [x] Run PHP lint, JavaScript syntax checks, and both test commands.
- [x] Add a push/PR CI workflow and commit checks together.

Verification: `node tools/check.mjs` passes on PHP 8.3.28 and Node.js 24.14.0.
Composer strict validation uses `--no-check-version`: phpBB requires the version
field, unlike ordinary Packagist packages. CI's other PHP versions remain unverified locally.

### 3. Release packaging
- [ ] Add a test asserting ZIP file list and runtime bytes match tracked files.
- [ ] Confirm the archive test rejects the current unfiltered repository archive.
- [ ] Add export exclusions and local artifact ignores.
- [ ] Add a release command rejecting dirty trees and mismatched version tags.
- [ ] Add tag CI that reruns checks, validates the ZIP, and uploads a release asset.
- [ ] Document release commands; run all checks and package validation.
- [ ] Commit packaging, workflow, tests, and release instructions together.

### 4. Final verification
- [ ] Review the complete diff and run all checks against committed files.
- [ ] Report commit IDs and any remaining publication steps.
