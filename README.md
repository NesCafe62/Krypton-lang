# Krypton lang

Idea of a new strong-typed language that will be familiar for JavaScript developers, but with native and crossplatform compilation (hopefully better performance and less runtime/compiler complexity) and zero dependencies if possible (that can run even on older machines). Not targeting web platform for now, but might be considered (using wasm or vanilla js, last one can be tricky). No plans for Mac or embedded for now, possible in future by adding LLVM IR target.

## Key goals
- Strong-typed. Increase the benefits of static analysis
- Simple, easy to learn (Not as complicated as rust and c++, but not too simple as go). No macros if possible
- Short and pretty syntax, wihout complication of control flow. No `public abstract static final synchronized` or things like `void ** (*d) (int &, char **(*)(char *, char **))`
- Minimal dependencies
- Mainly focused on native compilation
- Cross-platform
- Extendable language features (including syntax sugar) by extensions. Make it so simple, that every programmer can do it easily
- Multi-paradigm. Procedural, functional, OOP (same capabilities as in JS and TS, maybe with addition of pipe operator `|>`)


---
## Language specs
> listed only currently implemented types and features

### Types
`Int32` - 32-bit integer

`Bool` - boolean `true|false` (internally 0 or 1 32-bit integer for now)


`int` - `Int32` type alias

`bool` - `Bool` type alias

---
### Variables
Immutable by default
```js
int x; // variable declaration
x = 1; // initialization
x = 3; // error

int x2 = 1; // declaration with initialization

let y = 2; // type inferred as int
```
Mutable variables
```js
mut int x = 1;
x = 3; // no error

mut y = 2; // type inferred as int
```

---
### Operators

Binary math (int, int -> int):
- left `+` right - addition
- left `-` right - subtraction
- left `*` right - multiplication
- left `/` right - integer division
- left `%` right - integer division reminder

Binary bitwise (int, int -> int | bool, bool -> bool):
- left `&` right - butwise and
- left `|` right - butwise or
- left `^` right - butwise xor (exclusive or)

Binary bitshift (int, int -> int):
- left `>>` right - bit shift right
- left `<<` right - bit shift left

Binary comparison (int, int -> bool | bool, bool -> bool):
- left `==` right - bit shift right
- left `!=` right - bit shift right

(int, int -> int)
- left `<` right - less than
- left `<=` right - less or equal
- left `>` right - greater than
- left `>=` right - greater or equal

Unary:
- `-` (int right) - unary minus (negation)
- `+` (int right) - unary plus
- `~` (int right) - birwise not
- `!` (bool right) - boolean not

Assignment binary operators (int, int -> int):
- var `+=` right
- var `-=` right
- var `*=` right
- var `/=` right
- var `%=` right
- var `<<=` right
- var `>>=` right

(int, int -> int | bool, bool -> bool)
- var `&=` right
- var `|=` right
- var `^=` right

Assignment unary operators (int -> int):
- var `++`
- var `--`
- `++` var
- `--` var

---
### Branching
```js
if (x < 0) {
  // ...
} else if (x == 0) {
  // ...
} else {
  // ...
}
```

### `While` loop
```js
mut int x = 1;
while (x < 10) {
  x++;
  io.print(x);
}
```

### `For` loop
```js
for (mut int i in 1..10) { // excluding 10
  // ...
}

for (mut int i in 1..=10) { // including 10
  // ...
}
```

### Type aliases
```js
type i32 = int;
i32 x = 1; // int variable

type Index = i32;
Index x = 3; // also int variable
```

---
## Implemented

- [X] Math operations with precedence and grouping
- [X] Variables (mutable/immutable)
- [X] Branching and loops (`for`, `while`)
- [X] Sinlge line, multi-line comments
- [X] Variables check initialized before use (with consideration of branching)
- [X] Immutable variables check no reassignment, initialized only once (with consideration of branching)
- [X] Type checking (need testing)
- [X] Type inference (need testing)
- [X] Type aliases (need testing)
- [X] Transforming AST and tokens stream by compiler extensions (built-in and custom)
- [X] Dead code removing (uncovered edgecases)
- [X] Compile time const expression evaluation (uncovered edgecases)
- [X] Support for generic types in tokenizer (`<`, `>`, `>>` tokens not to be confused with operators. tracking if identifier is a declared type name). to better support extensions that consume/transform a stream of tokens

## Todo

- [ ] Update grammar.txt (it's outdated)
- [ ] Other primitive types (currently only `int` and `bool`)
- [ ] Nested multi-line comments (`/* /* ... */ */`)
- [ ] Implement generator for linux fasm and test it
- [ ] Type casting (explicit)
- [ ] String interpolation ``` `x = ${x}` ```
- [ ] `use` operator for scopes
- [ ] Modules (imports/exports)
- [ ] Number literals syntax (`0xFF`, `0b0101`, `1_250_000`)
- [ ] Strings, arrays, objects (structs and/or classes, need research)
- [ ] Functions, `Fn<>` types, lambdas
- [ ] Tuple types (as stack or register allocations)
- [ ] Generic types support
- [ ] Option/Nullable type (`Opt<T>`)
- [ ] Iterators (`Enumerable<T>`)
- [ ] Custom operator definitions
- [ ] Refs {probably}
- [ ] Built-ins like `Map<K,T>`, `Set<T>`


## Compiler extensions

Built-in:

- ExtCheckTypesAndVariables

- ExtEvaluateConstExpressions

- ExtRemoveDeadCode

Making custom extension:

... todo readme


## Compilation targets

- [X] Win x86 64-bit (fasm)
- [ ] Linux x86 64-bit (fasm)
- [ ] Win x86 64-bit (nasm) {probably}
- [ ] Linux x86 64-bit (nasm) {probably}
- [ ] LLVM IR {probably}


---
## Installation
Requirements:

- php 7.2 or higher (php used as a temporary language)

- flat assembly (fasm) util to compile .asm to binary

1. Clone the repo
```cd
$ cd project-path
$ git clone https://github.com/NesCafe62/Krypton-lang.git .
```

2. Add `php` to system path (optional)

## Usage

compile .kr file to .asm
```sh
$ php src/index.php test.kr > test.asm
```

compile .kr file and dump AST with `-ast` flag
```sh
$ php src/index.php test.kr -ast > test.ast
```

compile .kr file and compile to binary using fasm (command for windows)
```sh
php src/index.php test.kr > test.asm && fasm.exe test.asm
```
