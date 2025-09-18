<?php

namespace Component\Core\Adapter {

    trait Unix {

        final public function syntax(string $thread) {
            if (!\str_contains(($response = \exec("php -l " . $thread)), "No syntax errors detected")) {
                return (string) $response;
            }
        }

        final public function kill($pid, $signal): void {
            if (\posix_getpgid($pid)) {
                \posix_kill($pid, $signal);
            }
        }

        final public function exec(string $thread, int $sleep = 0, int $timeout = 300, bool|string $output = false): int {
            if (empty($output)) {
                $output = "/dev/null";
            }

            return (int) \exec(\sprintf("sleep %s && timeout %s php -f %s > %s & echo $!", $sleep, $timeout, $thread, $output));
        }

    }

}