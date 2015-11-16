$(document).ready(function() {
    $('#inserisci_prodotto').bootstrapValidator({

        message: 'Il valore non è valido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            nome: {
                message: 'Il nome non è valido',
                validators: {
                    notEmpty: {
                        message: 'Il nome è richiesto e non può essere vuoto'
                    }
                }
            },
            descrizione: {
                message: 'La descrizione non è valida',
                validators: {
                    notEmpty: {
                        message: 'La descrizione è richiesta e non può essere vuota'
                    }
                }
            },
            id: {
                message: 'L\'id non è valido',
                validators: {
                    notEmpty: {
                        message: 'L\'id è richiesto e non può essere vuoto'
                    }
                }
            },
            categoria: {
                message: 'La categoria non è valida',
                validators: {
                    notEmpty: {
                        message: 'La categoria è richiesta e non può essere vuota'
                    }
                }
            },
            sottocategoria1: {
                message: 'La sotto categoria 1 non è valida',
                validators: {
                    notEmpty: {
                        message: 'La sotto categoria 1 è richiesta e non può essere vuota'
                    }
                }
            },
            sottocategoria2: {
                message: 'La sotto categoria 2 non è valida',
                validators: {
                    notEmpty: {
                        message: 'La sotto categoria 2 è richiesta e non può essere vuota'
                    }
                }
            },
            brand: {
                message: 'Il brand non è valido',
                validators: {
                    notEmpty: {
                        message: 'Il super brand è richiesto e non può essere vuoto'
                    }
                }
            },
            stagione: {
                message: 'La stagione non è valida',
                validators: {
                    notEmpty: {
                        message: 'La stagione è richiesta e non può essere vuota'
                    }
                }
            },
            anno: {
                message: 'L\'anno non è valido',
                validators: {
                    notEmpty: {
                        message: 'L\'anno è richiesto e non può essere vuoto'
                    }
                }
            },

            prezzo: {
                message: 'Il prezzo non è valido',
                validators: {
                    notEmpty: {
                        message: 'Il prezzo è richiesto e non può essere vuoto'
                    }
                }
            },

            immagini: {
                message: 'Le immagini sono richieste e non possono essere vuote',
                validators: {
                    notEmpty: {
                        message: 'Le immagini sono richieste e non possono essere vuote'
                    }
                }
            }


        }
    });
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
                        message: 'La stagione è richiesto e non può essere vuoto'
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

	$('#dettaglio_spedizione').bootstrapValidator({

		message: 'Il valore non è valido',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
            'wgtntpro[totalpackages]': {
				message: 'Il numero colli non è valido',
				validators: {
					notEmpty: {
						message: 'Il numero colli è richiesto e non può essere vuoto'
					},
                    digits: {
                        message: 'Il numero colli non è valido'
                    }
				}
			},
			'wgtntpro[actualweight]': {
				message: 'Il peso totale dei colli non è valido',
				validators: {
					notEmpty: {
						message: 'Il peso totale dei colli è richiesto e non può essere vuoto'
					},
                    numeric: {
                        message: 'Il peso totale dei colli non è valido'
                    }
				}
			},
            'wgtntpro[actualvolume]': {
                message: 'Il volume totale dei colli non è valido',
                validators: {
                    notEmpty: {
                        message: 'Il volume totale dei colli è richiesto e non può essere vuoto'
                    },
                    numeric: {
                        message: 'Il volume totale dei colli non è valido'
                    }
                }
            },
            'wgtntpro[collectiondate]': {
                message: 'la data non è valida',
                validators: {
                    notEmpty: {
                        message: 'La data è richiesta e non può essere vuota'
                    }
                    ,
                    callback: {
                        message: 'La data non è valida',
                        callback: function (value, validator) {
                            var m = new moment(value, 'YYYYMMDD', true);
                            if (!m.isValid()) {
                                return false;
                            }
                            return true;
                        }
                    }
                }
            }



		}
	});
});

