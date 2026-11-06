<?php
/** Verify an installable ZIP against a committed Git tree without extracting it. */

$archive = $argv[1] ?? '';
$ref = $argv[2] ?? 'HEAD';
$zip = new ZipArchive();
if ($zip->open($archive) !== true)
{
	throw new RuntimeException('Cannot open release ZIP: ' . $archive);
}

function git_output($arguments)
{
	$command = 'git ' . implode(' ', array_map('escapeshellarg', $arguments));
	$process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
	$output = stream_get_contents($pipes[1]);
	$error = stream_get_contents($pipes[2]);
	fclose($pipes[1]);
	fclose($pipes[2]);
	if (proc_close($process) !== 0)
	{
		throw new RuntimeException($error);
	}
	return $output;
}

$expected = explode("\n", trim(git_output(['ls-tree', '-r', '--name-only', $ref, '--', 'dsr/modernadminpanel/'])));
$actual = [];
for ($i = 0; $i < $zip->numFiles; $i++)
{
	$name = $zip->getNameIndex($i);
	if (substr($name, -1) === '/')
	{
		continue;
	}
	$actual[] = $name;
}
sort($expected);
sort($actual);
if ($actual !== $expected || !$actual)
{
	throw new RuntimeException('Release ZIP must contain only the complete dsr/modernadminpanel/ runtime');
}
foreach ($expected as $file)
{
	if ($zip->getFromName($file) !== git_output(['show', $ref . ':' . $file]))
	{
		throw new RuntimeException('Release ZIP differs from Git: ' . $file);
	}
}
$zip->close();
echo 'Release ZIP: ' . count($actual) . " runtime files match Git\n";
