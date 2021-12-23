# ldl-container-builder

Builds a Symfony container from a collection of service files found in different directories
The service files themselves can be written in different formats (yml, xml, ini or php)

This builder will compile all of those files to one format (yml, xml, ini or php)

##### Find all services.xml, services.ini, services.yml, services.php files on a directory, write result as out.xml

```
php bin/build container:build out.xml /path/to/your/project 
```

##### Example 2, find all services.xml, load first custom1.xml and custom2.xml

```
php bin/build container:build out.xml xml -d /path/to/your/project -l services.xml -f path/to/custom1.xml, /path/to/custom2.xml
```


##### Example 2, find all services.xml, match compiler passes with a pattern

```
php bin/build container:build out.xml xml -d /path/to/your/project -l services.xml -p MyCompilerpass.php
```

## Compiler passes

#### IMPORTANT:  As we can't handle whatever you pass into a compiler pass constructor,  every compiler pass you have must extend to LDLAbstractCompilerPass

### Controlling compiler pass priority and type

For using compiler pass priority and type, make your compiler pass extends to LDLAbstractCompilerPass

##### Example 

```php
<?php

use LDL\DependencyInjection\CompilerPass\LDLAbstractCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MyCompilerPass extends LDLAbstractCompilerPass
{
    public function getPriority() : int
    {
        return 0;
    }
    
    public function getType() : string
    {
        return PassConfig::TYPE_OPTIMIZE;
    }
    
    public function process(ContainerBuilder $container)
    {
        // Implement process() method.
    }
    
}
```

## TODO

- Add excluded files and directories to command
