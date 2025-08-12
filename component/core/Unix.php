<?php

namespace Component\Core {

    trait Unix {

        final public function cmd(string $command): mixed {
            return \exec($command);
        }

        final public function kill($pid, $signal): void {
            if (\posix_getpgid($pid)) {
                \posix_kill($pid, $signal);
            }
        }
    }

}