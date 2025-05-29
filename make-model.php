<?php

if ($argc < 2) {
    echo "Usage: php make-model.php ModelName\n";
    exit(1);
}

$modelName = $argv[1];
$directory = __DIR__ . '/app/Models';  // Adjust path if needed
$filePath = "$directory/$modelName.php";

// Check if the directory exists or create it
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

// Check if the model file already exists
if (file_exists($filePath)) {
    echo "Model $modelName already exists.\n";
    exit(1);
}

// Define a basic model template
$modelTemplate = <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class $modelName extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected \$table = ''.$modelName.'';
    protected \$fillable = [];
}

PHP;

// Create the model file
file_put_contents($filePath, $modelTemplate);

echo "Model $modelName created successfully at $filePath.\n";
