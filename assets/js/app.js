
var validationRules = [];

function addValidationRule(elementID, rule) {
    if (typeof validationRules[elementID] == "undefined") {
        validationRules[elementID] = [];
        validationRules[elementID].push(rule);
    } else {
        validationRules[elementID].push(rule);
    }
}

/**
 * Field validation.
 * @var rule -- validation rule for current field.
 * @param field
 * @param rule
 */
function validate(field, rule) {
    if (Array.isArray(rule)) {

        var arraySize = rule.length;
        var i = 0;

        removeError(field);

        /**
         * rule[i][0] -- rule name
         */
        for (; i < arraySize;) {
            switch(rule[i][0]) {

                case 'required': requiredValidator(field, rule[i]); break;
                case 'string': stringValidator(field, rule[i]); break;
                case 'email': emailValidator(field, rule[i]); break;
                case 'login': loginValidator(field, rule[i]); break;
                case 'file': fileValidator(field, rule[i]); break;

                default:
                    console.log(i + ' of ' + arraySize);
                    break;
            }
            i++;
        }
    }
}

/**
 * Register "onChange" and "onFocusOut" handlers for any form fields.
 * @param formID
 */
function formValidation(formID) {
    var form = $('#' + formID);
    var fields = form.find('input, textarea');

    fields.on('focusout', function (field) {
        validate(field.target, validationRules[field.target.id]);
    });
}

function addError(field, message) {
    var input = $('#' + field.id);
    var errorBlockID = 'errors' + field.id;
    var errorTag = $('<div id="' + errorBlockID + '"></div>');
    var errorBlock;
    var errors;
    var error;

    if (input.attr('type')) {
        error = '<div class="validationErrorMessage">' + message + '</div>';
    } else {
        error = '<div class="textareaValidationErrorMessage">' + message + '</div>';
    }

    input.removeClass('has-success');
    input.addClass('has-error');

    if (document.getElementById(errorBlockID)) {
        errorBlock = $('#' + errorBlockID);
        errorBlock.append(error);
    } else {
        input.after(errorTag.fadeIn(600));
        errorBlock = $('#' + errorBlockID);
        errorBlock.append(error);
    }
}

function removeError(field) {
    var input = $('#' + field.id);
    $('#errors' + field.id).fadeOut(300).remove();

    input.removeClass('has-error');
    input.addClass('has-success');
}


function requiredValidator(field, rule) {
    if (field.value == "" || field.value.length == 0) {
        addError(field, rule[1]);
    }
}

function stringValidator(field, rule) {
    if(field.value.length < rule[1] || field.value.length > rule[2]) {
        addError(field, rule[3]);
    }
}

function emailValidator(field, rule) {
    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (regex.test(field.value) === false) {
        addError(field, rule[1]);
        return;
    }

    $.ajax({
        url: '/ajax/email/' + field.value,
        success: function(json) {
            if (json.success && json.count > 0) {
                addError(field, rule[2]);
            }
        }
    });
}

function loginValidator(field, rule) {
    var regex = /^[^0-9@#_-]\w+[^-_]$/;

    if(field.value.length < rule[1] || field.value.length > rule[2] || regex.test(field.value) === false) {
        addError(field, rule[3]);
        return;
    }

    $.ajax({
        url: '/ajax/login/' + field.value,
        success: function(json) {
            if (json.success && json.count > 0) {
                addError(field, rule[4]);
            }
        }
    });
}

function fileValidator(field, rule) {
    var filename = field.value.toLowerCase();
    var regex = new RegExp('(.*?)\.(' + rule[1] + ')$');

    if(field.value.length > 0 && regex.test(filename) === false){
        addError(field, rule[2]);
    }
}