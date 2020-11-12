function BeeObject() {}

BeeObject.prototype.formatAsCurrency = function(value, unit = 'NGN') {
    if (!value) {
        value = 0;
    }
    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "NGN",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    const newNumber = formatter.format(value);

    const withoutUnit = newNumber.split("NGN");

    if (withoutUnit === undefined) return newNumber;
    if (withoutUnit.length < 2) {
        return withoutUnit[0].split("₦")[1];
    }

    return ((!unit || unit === 'NGN') ? "₦" : '$') + withoutUnit[1];
};

BeeObject.prototype.getRandomId = function() {
    return Math.ceil(Math.random() * 10000000);
};



window.bee = new BeeObject();
