var express = require("express");
var app     = express();
var path = require('path');

app.use(express.static(__dirname + '/../public_one'));
//Store all HTML files in view folder.

app.get('/',function(req,res){
  res.sendFile(path.join(__dirname + '/../public_one/index.html'));
  //It will find and locate index.html from View or Scripts
});

app.get('/about',function(req,res){
  res.sendFile('/about.html');
});

app.get('/sitemap',function(req,res){
  res.sendFile('/sitemap.html');
});

app.listen(3000);

console.log("Running at Port 3000");