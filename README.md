
# Tower of Hanoi - PHP API

This project is a simple PHP API that allows users to play the Tower of Hanoi game with 7 disks and 3 pegs. The API provides endpoints to view the current state of the game and make moves by transferring disks between pegs. The game state is stored in memory using PHP sessions.

## Requirements

- **PHP 7.4** or higher
- **Composer** (for dependency management)
- A local server environment (e.g., PHP's built-in server)

## Endpoints

### 1. GET `/state`
Retrieve the current state of the game, including the position of all disks on the pegs and whether the game is finished.

**Response Example:**
```json
{
  "pegs": [
    [7, 6, 5, 4, 3, 2, 1],
    [],
    []
  ],
  "game_over": false
}
```

### 2. POST `/move/{from}/{to}`
Move the top disk from peg `{from}` to peg `{to}`. Pegs are indexed from 1 to 3.

**Request Example:**
```bash
curl -X POST http://localhost:8080/move/1/2
```

**Response Example:**
```json
{
  "success": true,
  "state": [
    [7, 6, 5, 4, 3, 2],
    [1],
    []
  ]
}
```

### Validation Errors:
If you attempt an invalid move (e.g., moving a larger disk onto a smaller one), the API will respond with:
- `400 Bad Request` and an appropriate error message:
  - **"No disks to move on the source peg."**
  - **"Invalid move: Cannot place a larger disk on a smaller disk."**
  - **"Game is already finished."**

## How to Run the Code

1. **Clone the Repository**
   ```bash
   git clone https://github.com/EugeneKyale/Tower-of-Hanoi-API
   cd Tower-of-Hanoi-API
   ```

2. **Install Dependencies**
   Run the following command to install development tools for static analysis:
   ```bash
   composer install
   ```

3. **Start the Server**
   You can use PHP's built-in web server to run the API:
   ```bash
   php -S localhost:8080
   ```

4. **Test the Endpoints**
   - Use your browser or a tool like `curl` to make requests to the API:
     - **GET** `/state`: View the current game state.
     - **POST** `/move/{from}/{to}`: Move a disk from one peg to another.

### Example Requests:
- **Get Game State**:
  ```bash
  curl http://localhost:8080/state
  ```

- **Move Disk from Peg 1 to Peg 2**:
  ```bash
  curl -X POST http://localhost:8080/move/1/2
  ```

## Game Rules

The Tower of Hanoi is a puzzle consisting of three pegs and multiple disks of different sizes. The goal is to move all the disks from the first peg to the third peg while following these rules:
1. Only one disk can be moved at a time.
2. A disk can only be moved to the top of another peg.
3. No larger disk may be placed on top of a smaller disk.

## Static Analysis

The project includes configuration for two static analysis tools, **Psalm** and **PHPStan**. These tools help ensure code quality and correctness.

- **Run Psalm**:
  ```bash
  vendor/bin/psalm
  ```

- **Run PHPStan**:
  ```bash
  vendor/bin/phpstan analyse
  ```

## Future Improvements

- Implement persistent storage using a database.
- Add support for multiple simultaneous games.
- Create a front-end interface to visualize the game state.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

---

Enjoy solving the puzzle!
