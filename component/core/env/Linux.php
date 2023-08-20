<?php

namespace Component\Core\Env {

    final class Linux extends \Component\Core\Env {

        protected function getPid(): int {
            return (int) \getmypid();
        }

        protected function getProc(): int {
            return (int) $this->dehydrate(\exec("nproc"));
        }

        protected function getLoad(): float {

            return (float) \implode(false, \array_slice(\sys_getloadavg(), 0, 1));
        }

        protected function ping(int|string $pid): bool {
            return (bool) \file_exists("/proc/" . $pid);
        }
    }

}