<!DOCTYPE html>
<html>
    <head>
<!-- jQuery 2.1.4 -->
        <script src="/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    </head>
    <body>
    <script>

    $.ajax({
      url: 'https://secure.snd.payu.com/api/v2_1/orders',
      type: 'POST',
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer 02e26faf-b148-4f98-98bc-ecd36305d865"
      },
      data: {
        "notifyUrl": "https://your.eshop.com/notify",
        "customerIp": "127.0.0.1",
        "merchantPosId": "300046",
        "description": "RTV market",
        "currencyCode": "PLN",
        "totalAmount": "21000",
        "buyer": {
                "email": "john.doe@example.com",
                "phone": "654111654",
                "firstName": "John",
                "lastName": "Doe",
                "language": "pl"
        },
        "products": [
            {
                "name": "Wireless Mouse for Laptop",
                "unitPrice": "15000",
                "quantity": "1"
            },
            {
                 "name": "HDMI cable",
                 "unitPrice": "6000",
                 "quantity": "1"
            }
        ]
      },
      success: function(res){
        console.log('Paypal ==>');
        console.log(res);
      }
    
    });

    </script>
    </body>
</html>