<?php

// Controller
use TLC\Application\Model\Policyholder;

$policyholders = Policyholder::fetchAllPolicyholders();
echo $twig->render('dashboard.twig', ['policyholders' => $policyholders]);
