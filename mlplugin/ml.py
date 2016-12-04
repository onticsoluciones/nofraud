#!/usr/bin/env python2
import sys
import os
import math
import fileinput
import json
import tensorflow as tf
from transaction import Transaction

class Model(object):

    stateFile = os.path.dirname(os.path.realpath(__file__)) + "/data/model.ckpt"

    def __init__(self):

        numberOfFactors = 2

        x = tf.placeholder(tf.float32, [None, numberOfFactors])
        y = tf.placeholder(tf.float32, [None, 2])
        y_ = tf.placeholder(tf.float32, [None, 2])

        W = tf.Variable(tf.zeros([numberOfFactors, 2]))
        b = tf.Variable(tf.zeros([2]))

        init = tf.global_variables_initializer()

        y = tf.nn.softmax(tf.matmul(x, W) + b)
        cross_entropy = tf.reduce_mean(-tf.reduce_sum(y_ * tf.log(y), reduction_indices=[1]))
        error = tf.square(y - y_)
        optimizer = tf.train.GradientDescentOptimizer(0.001).minimize(error)

        sess = tf.Session()
        sess.run(init)

        self.y = y
        self.y_ = y_
        self.x = x
        self.sess = sess
        self.optimizer = optimizer

    def learn(self, x, y_):
        self.sess.run(self.optimizer, feed_dict={self.x: x, self.y_: y_})

    def evaluate(self, x):
        return self.sess.run(self.y, feed_dict={self.x: x})[0][1]

    def load(self):
        if os.path.exists(Model.stateFile + ".index"):
            saver = tf.train.Saver()
            saver.restore(self.sess, Model.stateFile)

    def save(self):
        directory = os.path.dirname(Model.stateFile)
        if not os.path.exists(directory):
            os.makedirs(directory)
        saver = tf.train.Saver()
        saver.save(self.sess, Model.stateFile)

def train():

    model = Model()
    model.load()

    transactions = json.load(sys.stdin)

    for info in transactions:
        amount = info["amount"]
        shipToSameCountry = info["ship_to_same_country"]
        valid = info["valid"]
        transaction = Transaction(amount, shipToSameCountry, valid)
        model.learn([transaction.getX()], [transaction.getY()])

    model.save()


def evaluate():

    model = Model()
    model.load()

    transactions = json.load(sys.stdin)

    for info in transactions:
        amount = info["amount"]
        shipToSameCountry = info["ship_to_same_country"]
        transaction = Transaction(amount, shipToSameCountry, None)
        print model.evaluate([transaction.getX()])

if __name__ == "__main__":

    if "--train" in sys.argv:
        train()
    elif "--evaluate" in sys.argv:
        evaluate()
    else:
        print "Usage: {} [--train|--evaluate]".format(sys.argv[0])
