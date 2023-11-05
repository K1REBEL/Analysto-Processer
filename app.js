require("dotenv").config();

const express = require("express");
const app = express();
app.use(express.json());
const port = 4000;

app.get("/", (req, res) => res.send("Hello Analysto!"));

app.listen(port, "0.0.0.0", () => console.log("Server is running on port " + port));