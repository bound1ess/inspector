# Inspector

## What is this?

My attempt to implement a code coverage analysis tool for PHP without help of written in *C* extensions (e.g. *Xdebug*).

I managed to write a very simple prototype showing *what it could look like*:
```shell
git clone https://github.com/bound1ess/inspector.git
cd inspector
make first-example
make second-example
```

### Disclaimer

This is just a PoC, not a ready-to-go product: code in this repository is very unstable, has tons of bugs, some vital features are missing etc.

## Why would you do it?

For the purpose of challenging myself, learning new things and improving skills.

## And what now?

I came to the conclusion that this is definitely possible, BUT:
- the amount of time it takes to run (especially for large codebases) is really huge (compared to, say, Xdebug)
- there are some cases where analysing and modifying AST inside of PHP is just *not enough*

I permanently stopped working on this project.
