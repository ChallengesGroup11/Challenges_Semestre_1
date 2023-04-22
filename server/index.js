"use strict";

const express = require("express");
const bodyParser = require("body-parser");
const multer = require("multer");
const upload = multer();
const cors = require("cors");

const app = express();

app.use(cors("*"));
app.use(bodyParser.json());
// app.use(bodyParser.urlencoded({ extended: true }));
// app.use(upload.array());
// app.use(express.static('public'));

// Constants
const PORT = 3200;

const checkFileType = (req, res, next) => {
  const fileTypes = /^application\/pdf$|^image\/(jpeg|jpg)$/;

  const fileCodeType = fileTypes.test(req.files.fileCode[0].mimetype);
  const fileCniType = fileTypes.test(req.files.fileCni[0].mimetype);

  console.log(fileCniType);

  if (fileCodeType && fileCniType) {
    next();
  } else {
    res.status(400).send("Invalid file type");
  }
};

const checkFileTypeDirector = (req, res, next) => {
  const fileTypes = /^application\/pdf$|^image\/(jpeg|jpg)$/;

  const file = fileTypes.test(req.files.file[0].mimetype);
  console.log(file)
  if (file) {
    next();
  } else {
    res.status(400).send("Invalid file type");
  }
};

app.get("/", (req, res) => {
  res.send("Hello World");
});

app.post(
  "/api/student",
  upload.fields([{ name: "fileCode" }, { name: "fileCni" }]),
  checkFileType,
  (req, res) => {
    setTimeout(() => {
      res.status(200).send("Document are valid");
    }, 2000);
  }
);

app.post("/api/director", upload.fields([{ name: "file" }]),
  checkFileTypeDirector,
  (req, res) => {
    setTimeout(() => {
      res.status(200).send("Document are valid");
    }, 2000);
  });

app.listen(PORT, () => {
  console.log(`Running on ${PORT}`);
});
