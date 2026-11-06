# Release checklist

This repository distributes the ready-to-install free edition. Do not import
private authoring sources or run an asset compilation step.

## Check locally

Use PHP with the ZIP extension, Node.js 24, Git, and Composer:

```sh
node tools/check.mjs
composer validate --strict --no-check-version --no-check-publish --no-check-lock dsr/modernadminpanel/composer.json
```

Checks cover PHP and JavaScript syntax, option validation, palette permissions,
plain-text sanitation, translation keys, update metadata, bundled asset paths,
local documentation links, and release packaging. PHP helper tests use small
input doubles; they do not replace installation and browser testing on phpBB.
CI repeats these checks with PHP 7.1, 7.4, 8.3, and 8.5.

## Build the installable ZIP

Commit all intended changes first. The packaging command rejects tracked changes
and untracked files, but ignores local output and editor folders:

```sh
node tools/package.mjs
```

Output: `dist/dsr_modernadminpanel_2.0.0.zip` for version 2.0.0. The filename
follows `composer.json`. The ZIP contains only `dsr/modernadminpanel/`, including
the GPL text and icon licence. It is extracted into phpBB's `ext/` directory.
Documentation remains in this repository, not the installable ZIP.

`git archive` reads committed files and `.gitattributes`. The archive check
compares every runtime path and byte against that commit. Root documentation,
tests, workflows, the version feed, and local files cannot enter the release.

## Publish

1. Test installation, settings, navigation, searches, and disabling on a test
   phpBB board. Check both included languages and narrow-screen navigation.
2. Update `dsr/modernadminpanel/composer.json`, `last_ext_version.json`, and the
   changelog together. Keep the feed's stable branch, version, announcement URL,
   and ZIP URL consistent. Update version-specific installation documentation.
3. Run the checks, then commit. Keep the public feed on `master` in sync with
   the release; phpBB reads that branch rather than the version tag.
4. Create the version tag on the checked commit, then verify its package:

   ```sh
   git tag v2.0.0
   node tools/package.mjs --tag v2.0.0
   ```

5. Push the branch and tag when ready to publish:

   ```sh
   git push origin master
   git push origin v2.0.0
   ```

The tag workflow reruns checks, rejects mismatched versions, validates the ZIP,
and creates a GitHub release with the installable asset. A failed check prevents
publication. Do not move a published tag; fix mistakes in a new release.

The repository, feed, and release asset must be publicly accessible for ordinary
phpBB installations. After publication, verify the feed and ZIP without GitHub
credentials and test the ACP's native version check. Nothing installs updates
automatically on a user's board.
