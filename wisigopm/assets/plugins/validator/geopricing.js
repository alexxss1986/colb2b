$(document).ready(function() {
    $('#inserisci_geo').bootstrapValidator({

        message: 'Il valore non è valido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            'store': {
                message: 'Lo store non è valido',
                validators: {
                    notEmpty: {
                        message: 'Lo store è richiesto e non può essere vuoto'
                    }
                }
            },
            'brand': {
                message: 'Il brand non è valido',
                validators: {
                    notEmpty: {
                        message: 'Il brand è richiesto e non può essere vuoto'
                    }
                }
            },
            'stagione': {
                message: 'La stagione non è valido',
                validators: {
                    notEmpty: {
                        message: 'La stagione è richiesta e non può essere vuoto'
                    }
                }
            },
            'anno': {
                message: 'L\'anno non è valido',
                validators: {
                    notEmpty: {
                        message: 'L\'anno è richiesto e non può essere vuoto'
                    }
                }
            },
            'markup': {
                message: 'Il mark up non è valido',
                validators: {
                    notEmpty: {
                        message: 'Il mark up è richiesto e non può essere vuoto'
                    },
                    numeric: {
                        message: 'Il mark up non è valido'
                    }
                }
            }
        }
    });
});

