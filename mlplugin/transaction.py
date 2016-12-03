import math

class Transaction(object):

    def __init__(self, orderAmount, shipToSameCountry, valid):
        self.orderAmount = orderAmount
        self.shipToSameCountry = shipToSameCountry
        self.valid = valid

    def getX(self):
        return [
            math.log(self.orderAmount),
            self.shipToSameCountry ]

    def getY(self):
        if self.valid:
            return [0, 1]
        else:
            return [1, 0]
