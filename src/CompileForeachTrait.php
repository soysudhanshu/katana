<?php

namespace Blade;

trait CompileForeachTrait
{
    protected array $foreachHashes = [];

    protected function compileForeach(string $expression): string
    {
        $hash = md5($expression . mt_rand());
        $this->foreachHashes[] = $hash;


        preg_match("/\((?'iterable'.*) as (?'value'(.*))\)/", $expression, $expressionContent);

        $iterable = $expressionContent['iterable'];
        $value = $expressionContent['value'];
        /**
         * We will back up current loop data
         * before starting a new loop, to
         * allow nested loops to work.
         */
        return sprintf('<?php $loop_%s = $loop ?? null; ?>', $hash) .
            "<?php \$loop = new \Blade\Loop(); ?>" .
            sprintf(
                '<?php foreach($loop->setData(%s, $loop_%s) as %s): ?>',
                $iterable,
                $hash,
                $value
            );
    }

    protected function compileEndforeach(string $expression): string
    {
        $hash = array_pop($this->foreachHashes);

        return "<?php \$loop->increment(); ?>" .
            "<?php endforeach; ?>" .
            "<?php \$loop = \$loop_$hash; ?>";
    }
}
