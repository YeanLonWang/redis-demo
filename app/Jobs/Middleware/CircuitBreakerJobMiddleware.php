<?php

namespace App\Jobs\Middleware;

class CircuitBreakerJobMiddleware
{
//    public function handle($job, $next)
//    {
//        $this->lastFailureTimestamp = Cache::get('circuit:open');
//
//        // Check if the circuit is open and release the job.
//        if (!$this->shouldRun()) {
//            return $job->release(
//                $this->lastFailureTimestamp +
//                $this->secondsToCloseCircuit + rand(1, 120)
//            );
//        }
//
//        // If the circuit is closed or half-open, we will try
//        // running the job and catch exceptions.
//        try {
//            $next($job);
//
//            // If the job passes, we'll close the circuit if it's
//            // open and reset the failures counter.
//            $this->closeCircuit();
//        } catch (RequestException $e) {
//            if ($e->response->serverError()) {
//                $this->handleFailure($job);
//            }
//        } catch (ConnectionException $e) {
//            $this->handleFailure($job);
//        }
//    }

}
