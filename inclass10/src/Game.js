import React from "react";
import GameGrid from "./GameGrid.js";

// TODO: Import useState() hook
import { useState } from "react";


function Game() {

   // TODO: Replace variables with state variables
   const [moves, setMoves] = useState(Array(9).fill(""));

   const [turn, setTurn] = useState("X");

   function gridClick(whichSquare) {
      // TODO: Replace with code to set the move and turn
      if (moves[whichSquare] === "") {
        // Copy current moves and update the clicked square
        const movesCopy = [...moves];
        movesCopy[whichSquare] = turn;
        setMoves(movesCopy);

        // Alternate turns between X and O
        setTurn(turn === "X" ? "O" : "X");
     }
   }

   // TODO: Add newGame() function here
   function newGame() {
    setMoves(Array(9).fill(""));
    setTurn("X");
 }


   // TODO: Make New Game button to call newGame() when clicked
   return (
      <>
         <h1>Tic-Tac-Toe</h1>
         <GameGrid moves={moves} click={gridClick} />
         <p>
            Turn: <strong className={turn}>{turn}</strong>
         </p>
         <p>
         <button onClick={newGame}>New Game</button>
         </p>
      </>
   );
}

export default Game;
