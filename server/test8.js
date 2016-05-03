var express = require("express");
var app     = express();
var path = require('path');
var email1 = require('./emailTest.js');

app.use(express.static(__dirname + '/../public_two'));
//Store all HTML files in view folder.

app.get('/',function(req,res){
  res.sendFile(path.join(__dirname + '/../public_two/index.html'));
  //It will find and locate index.html from View or Scripts
});

app.get('/email',function(req,res){
  //res.email1.sendTest();
});

app.get('/sitemap',function(req,res){
  res.sendFile('/sitemap.html');
});

app.listen(4000);

console.log("Running at Port 4000");


