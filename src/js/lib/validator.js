function isInt(value) {
    return value.indexOf('.') == -1 && isNaN(value) == false && parseInt(value) == value;
}

let validators = {
    blank: function (value, constraint, context) {
        let message = constraint.message || 'This value should be blank.';

        if ('' != value && null != value) {
            context.addViolation(message, value, {
                '{{ value }}': value
            });
        }
    },
    email: function (value, constraint, context) {
        let message = constraint.message || 'This value is not a valid email address.';

        if (null == value || '' == value) {
            return;
        }

        let reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        if (!reg.test(value)) {
            context.addViolation(message, value, {
                '{{ value }}': value
            });
        }
    },
    ip: function (value, constraint, context) {
        if (null == value || '' == value) {
            return;
        }

        let reg = /(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/;

        if (!reg.test(value)) {
            context.addViolation(constraint.message, value, {
                '{{ value }}': value
            });
        }
    },
    maxLength: function (value, constraint, context) {
        if (null == value || '' == value) {
            return;
        }

        if (value.length > parseFloat(constraint.limit)) {
            context.addViolation(constraint.message, value, {
                '{{ limit }}': constraint.limit,
                '{{ value }}': value
            });
        }
    },
    minLength: function (value, constraint, context) {
        if (null == value || '' == value) {
            return;
        }

        if (value.length < parseFloat(constraint.limit)) {
            context.addViolation(constraint.message, value, {
                '{{ limit }}': constraint.limit,
                '{{ value }}': value
            });
        }
    },
    notBlank: function (value, constraint, context) {
        let message = constraint.message || 'This value should not be blank.';

        if ('' == value || null == value) {
            context.addViolation(message, value);
        }
    },
    notNull: function (value, constraint, context) {
        let message = constraint.message || 'This value should not be null.';

        if ('' == value || null == value) {
            context.addViolation(message, value);
        }
    },
    regex: function (value, constraint, context) {
        if (null == value || '' == value) {
            return;
        }

        let reg = new RegExp(constraint.pattern);

        if (!reg.test(value)) {
            context.addViolation(constraint.message, value, {
                '{{ value }}': value
            });
        }
    },
    range: function (value, constraint, context) {
        let minMessage = constraint.minMessage || 'This value should be {{ limit }} or more.';
        let maxMessage = constraint.maxMessage || 'This value should be {{ limit }} or less.';
        let invalidMessage = constraint.invalidMessage || 'This value should be a valid number.';

        if (isNaN(value)) {
            context.addViolation(invalidMessage, value, {
                '{{ value }}': value
            });

            return;
        }

        if (constraint.max && parseFloat(value) > parseFloat(constraint.max)) {
            context.addViolation(maxMessage, value, {
                '{{ limit }}': constraint.max,
                '{{ value }}': value
            });
        }


        if (constraint.min && parseFloat(value) < parseFloat(constraint.min)) {
            context.addViolation(minMessage, value, {
                '{{ limit }}': constraint.min,
                '{{ value }}': value
            });
        }
    },
    size: function (value, constraint, context) {
        if (isNaN(value)) {
            context.addViolation(constraint.invalidMessage, value, {
                '{{ value }}': value
            });

            return;
        }

        if (parseFloat(value) < parseFloat(constraint.min)) {
            context.addViolation(constraint.minMessage, value, {
                '{{ limit }}': constraint.min,
                '{{ value }}': value
            });

            return;
        }

        if (parseFloat(value) > parseFloat(constraint.max)) {
            context.addViolation(constraint.maxMessage, value, {
                '{{ limit }}': constraint.max,
                '{{ value }}': value
            });
        }
    },
    sizeLength: function (value, constraint, context) {
        if (value.length < parseFloat(constraint.min)) {
            context.addViolation(constraint.minMessage, value, {
                '{{ limit }}': constraint.min,
                '{{ value }}': value
            });

            return;
        }

        if (value.length > parseFloat(constraint.max)) {
            context.addViolation(constraint.maxMessage, value, {
                '{{ limit }}': constraint.max,
                '{{ value }}': value
            });
        }
    },
    time: function (value, constraint, context) {
        if (null == value || '' == value) {
            return;
        }

        let reg = /^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]))?$/;

        if (!reg.test(value)) {
            context.addViolation(constraint.message, value, {
                '{{ value }}': value
            });
        }
    },
    url: function (value, constraint, context) {
        if (null == value || '' == value) {
            return;
        }

        let reg = /(?:https?:\/\/(?:(?:(?:(?:(?:[a-zA-Z\d](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?)\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\d]|-)*[a-zA-Z\d])?))|(?:(?:\d+)(?:\.(?:\d+)){3}))(?::(?:\d+))?)(?:\/(?:(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[;:@&=])*)(?:\/(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[;:@&=])*))*)(?:\?(?:(?:(?:[a-zA-Z\d$\-_.+!*'(),]|(?:%[a-fA-F\d]{2}))|[;:@&=])*))?)?)/;

        if (!reg.test(value)) {
            context.addViolation(constraint.message, value, {
                '{{ value }}': value
            });
        }
    },
    type: function (value, constraint, context) {
        if (null == value || '' == value) {
            return;
        }

        switch (constraint.type) {
            case 'int':
            case 'integer':
            case 'digit':
                if (value.indexOf('.') == -1 && isNaN(value) == false && parseInt(value) == value) {
                    return;
                }
                break;
            case 'numeric':
                if (isNaN(value) == false) {
                    return;
                }
                break;
            case 'string':
                if (typeof(value) == 'string') {
                    return;
                }
                break;
            default:
                return;
        }

        context.addViolation(constraint.message, value, {
            '{{ value }}': value,
            '{{ type }}': constraint.type
        });
    },
    'true': function (value, constraint, context) {
        if (!value) {
            context.addViolation(constraint.message, value);
        }
    },
    'false': function (value, constraint, context) {
        if (null == value) {
            return;
        }

        if (false == value || 0 == value) {
            return;
        }

        context.addViolation(constraint.message, value);
    }
};

export class Validator {
    constructor(validators = []) {
        this.validators = validators;
    }

    validate(value, constraints) {
        let context = new Context();

        if (typeof constraints == 'string') {
            constraints = [constraints];
        }

        for (let constraint in constraints) {
            if (!constraints.hasOwnProperty(constraint)) {
                continue;
            }

            let data = constraints[constraint];

            if (typeof data == 'string') {
                data = {
                    type: data
                };
            }

            if (typeof this.validators[data.type] != 'function') {
                throw new Error('validator for "' + data.type + '" not found!');
            }

            this.validators[data.type](value, data.options || {}, context);
        }

        return context.getViolations();
    }

    addValidator(type, callback) {
        this.validators[type] = callback;
    }
}

export class Context {
    constructor(validator) {
        this.validator = validator;
        this.violations = [];
    }

    addViolation(message, value, constraint) {
        this.violations.push(new Violation(message, value, constraint));
    }

    getViolations() {
        return this.violations;
    }
}

export class Violation {
    constructor(message, value, params) {
        this.message = message;
        this.value = value;
        this.params = params;
    }

    getMessage() {
        let message = this.message;

        for (let key in this.params) {
            if (!this.params.hasOwnProperty(key)) {
                continue;
            }

            message = message.replace(key, this.params[key]);
        }

        return message;
    }
}

export default new Validator(validators);