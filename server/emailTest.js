'use strict';
var directTransport = require('nodemailer-direct-transport');
var nodemailer = require('nodemailer');
var options = {};
var transporter = nodemailer.createTransport(directTransport(options))


//We can also use gmail as transport easily


var nodemailer = require('nodemailer');
var transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: 'kodakatdesigns@gmail.com',
        pass: 'Tabetha2170'
    }
});


transporter.sendMail({
    from: 'kodakatdesigns@gmail.com',
    to: 'carsonkramer55@yahoo.ca',
    subject: 'hello',
    html: 'hello world!'
})

var sendEmail= transporter.sendMail();

exports.sendTest = sendEmail;
