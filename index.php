<?php

/**
 * Tower of Hanoi Game API
 *
 * A simple HTTP API to play the Tower of Hanoi game with 7 disks and 3 pegs.
 * Supports the following API endpoints:
 * - GET /state: Retrieve the current game state.
 * - POST /move/{from}/{to}: Move a disk from one peg to another.
 *
 * @package LivTours
 */

// Start PHP session to store game state.
session_start();

// Initialize the game state if not already done.
if ( ! isset( $_SESSION['game_state'] ) ) {
	$_SESSION['game_state'] = [
		[ 7, 6, 5, 4, 3, 2, 1 ],  // Peg 1
		[],                       // Peg 2
		[]                        // Peg 3
	];
}

/**
 * Check if the game is finished.
 *
 * The game is considered finished if all disks are moved to the third peg.
 *
 * @param array $state The current state of the game.
 * 
 * @return bool True if the game is finished, false otherwise.
 */
function isGameFinished( $state ) {
	return empty( $state[0] ) && empty( $state[1] ) && count( $state[2] ) === 7;
}

// Handle API requests.
$method = $_SERVER['REQUEST_METHOD'];
$uri    = $_SERVER['REQUEST_URI'];

// GET /state to get the current game state.
if ( 'GET' === $method && '/state' === $uri ) {
	$response_data = [
		'pegs'      => $_SESSION['game_state'],
		'game_over' => isGameFinished( $_SESSION['game_state'] ),
	];
	header( 'Content-Type: application/json' );
	echo json_encode( $response_data );
	exit;
}

// POST /move/{from}/{to} to move a disk.
if ( 'POST' === $method && preg_match( '/\/move\/(\d+)\/(\d+)/', $uri, $matches ) ) {
	$from = (int) $matches[1] - 1;  // Convert peg number to zero-index.
	$to   = (int) $matches[2] - 1;

	$state = $_SESSION['game_state'];

	// Check if the game is already finished.
	if ( isGameFinished( $state ) ) {
		header( 'HTTP/1.1 400 Bad Request' );
		echo 'Game is already finished.';
		exit;
	}

	// Validate the move.
	if ( empty( $state[ $from ] ) ) {
		header( 'HTTP/1.1 400 Bad Request' );
		echo 'No disks to move on the source peg.';
		exit;
	}

	$disk = end( $state[ $from ] );

	if ( ! empty( $state[ $to ] ) && end( $state[ $to ] ) < $disk ) {
		header( 'HTTP/1.1 400 Bad Request' );
		echo 'Invalid move: Cannot place a larger disk on a smaller disk.';
		exit;
	}

	// Perform the move.
	array_pop( $state[ $from ] );
	array_push( $state[ $to ], $disk );
	$_SESSION['game_state'] = $state;

	header( 'Content-Type: application/json' );
	echo json_encode( [ 'success' => true, 'state' => $state ] );
	exit;
}

// If no route matches, return 404.
header( 'HTTP/1.1 404 Not Found' );
echo 'Endpoint not found.';
