import toast from "./toast";

function BeeObject() {}

BeeObject.prototype.formatAsCurrency = function(number) {
  var formatter = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "NGN",
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
  var newNumber = formatter.format(number);

  var withoutUnit = newNumber.split("NGN");

  if (withoutUnit === undefined) return newNumber;
  if (withoutUnit.length < 2) {
    return withoutUnit[0].split("₦")[1];
  }

  return "₦" + withoutUnit[1];
};

BeeObject.prototype.formatAsCurrency2 = function(number) {
  var formatter = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "NGN",
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
  var newNumber = formatter.format(number);

  var withoutUnit = newNumber.split("NGN");

  if (withoutUnit === undefined) return newNumber;
  if (withoutUnit.length < 2) {
    return withoutUnit[0].split("₦")[1];
  }

  return  withoutUnit[1];
};

const API_publicKey = "FLWPUBK-aa441bff02164a3e59ac6d8039b0eeee-X";

BeeObject.prototype.payWithRave = function(paymentData, callback) {
  if (typeof getpaidSetup === "undefined") {
    toast.info(
      "Unable to connect to our payment gateway, please ensure your internet connection is working."
    );
    return;
  }
  var x = getpaidSetup({
    PBFPubKey: API_publicKey,
    customer_email: paymentData.email,
    amount: paymentData.amount,
    customer_phone: paymentData.meta.phone,
    currency: "NGN",
    txref: paymentData.meta.ref,
    meta: [
      {
        metaname: "OrderId",
        metavalue: paymentData.meta.orderId
      }
    ],
    onclose: function() {},
    callback: function(response) {
      callback(response);
      x.close();
    }
  });
};

BeeObject.prototype.isValidUrl = function(url) {
    const pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))' + // ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + //port
        '(\\?[;&amp;a-z\\d%_.~+=-]*)?' + // query string
        '(\\#[-a-z\\d_]*)?$', 'i');
    return pattern.test(url);
};

BeeObject.prototype.getRandomId = function() {
    return Math.ceil(Math.random() * 10000000);
};



window.bee = new BeeObject();
