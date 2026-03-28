# CF Round 1086 (Div. 2) - Cyclists

## Rough Thinking Process

At first, this problem feels like a **queue simulation** problem with lots of choices:

- every turn, Bob may choose **any card among the first **``
- that chosen card moves to the **back**
- the deck order keeps changing
- Bob wants to play the special card as many times as possible under budget `m`

So the first scary thought is:

> Do we need to try many different strategies?

That would be way too complicated.

So instead, we try to figure out:

## What is the best move at any moment?

Suppose the current first `k` playable cards have costs like this:

- `2, 5, 8`

If the special card is **not** among them, then playing `5` or `8` instead of `2` only wastes more energy and does **not** help us more than the cheaper move.

So if the special card is not available, the best thing is to:

> always play the cheapest playable card

Now what if the special card **is** available?

Then we should clearly play it immediately, because:

- that directly increases the answer by `1`
- delaying it does not help
- and the code makes sure it is treated as the cheapest choice

So the whole strategy becomes:

> Among the first `k` cards, always choose the minimum-cost one, and if the win card is there, take it immediately.

That gives a greedy solution.

---

# Key Observation

## Observation 1: only the first `k` cards matter right now

At any turn, Bob is only allowed to choose from the first `k` positions.

So we only need a fast way to maintain:

- the current first `k` cards
- the rest of the deck behind them

---

## Observation 2: greedy choice = cheapest playable card

If the special card is not currently playable, then spending less energy now is always better than spending more.

So among the first `k` cards, we should always play the cheapest one.

If the special card is playable, we should play it immediately.

---

## Observation 3: special trick for the win card

The code stores the special card as `-1`.

Why?

Because all normal costs are positive, so `-1` is always the smallest element.

That means:

- if the special card is currently among the first `k`,
- it will automatically be chosen first by the greedy logic

But when paying for it, we use its **real cost** stored separately.

So `-1` is just a marker saying:

> "This is the special card."

---

# Data Structures

We split the deck into 2 parts:

## 1. `multiset<int> hand`

This stores the current **first **``** playable cards**.

Why a `multiset`?

Because we want to quickly:

- get the smallest playable card
- erase it
- insert the next incoming card

All of that is efficient with a multiset.

---

## 2. `queue<int> others`

This stores the rest of the deck behind the first `k`.

So the whole deck is represented as:

- `hand` = first `k` cards
- `others` = remaining `n-k` cards

When one card is played:

- it leaves `hand`
- goes to the back of the deck → so it gets pushed into `others`
- then the front of `others` moves up into `hand`

That exactly simulates the queue behavior.

---

# Step-by-Step Solution Explanation

## Step 1: Read input

We read:

- `n` = number of cards
- `k` = number of playable cards
- `p` = initial position of special card
- `m` = total budget

Then we do:

```cpp
p--;
```

because input positions are 1-based, but C++ uses 0-based indexing.

---

## Step 2: Build the initial deck representation

We read the `n` costs one by one.

If the current position is the special card:

- save its real cost in `realCost`
- store `-1` instead

Then:

- if this card is in the first `k`, insert into `hand`
- otherwise push into `others`

So after reading input:

- `hand` contains exactly the first `k` cards
- `others` contains the rest of the deck
- the special card is marked as `-1`

---

## Step 3: Repeatedly play the cheapest playable card

Inside the loop:

```cpp
int front = *(hand.begin());
hand.erase(hand.begin());
```

Since `multiset` is sorted, `hand.begin()` is the smallest playable card.

So this simulates:

> choose the cheapest card among the first `k`

---

## Step 4: Pay its cost

There are two cases.

### Case A: special card

If `front == -1`, then this is the win-condition card.

Its real cost is `realCost`.

So:

- if paying it would exceed budget, stop
- otherwise:
  - increase `counter`
  - add `realCost` to total cost

### Case B: normal card

If it is not `-1`, its value is its actual cost.

So:

- if paying it would exceed budget, stop
- otherwise add that cost to `totalPrice`

---

## Step 5: Simulate the deck rotation

After playing a card:

- it goes to the back of the deck
- the first card from `others` moves into the playable zone

So:

```cpp
others.push(front);
hand.insert(others.front());
others.pop();
```

This keeps the deck representation correct after each move.

---

# Why the simulation is correct

At all times, the code maintains this invariant:

## Invariant

- `hand` contains exactly the current first `k` cards
- `others` contains all remaining cards, in the correct order

Each move does exactly what the problem says:

1. choose a card from the first `k`
2. remove it
3. pay its cost
4. move it to the back
5. slide the next card forward into the first `k`

So the loop is a faithful simulation of the greedy strategy.

---

# Why the `-1` trick is clever

Suppose the first `k` cards are:

- costs `3, 7, S`

If the special card were stored with its real cost, say `5`, then the smallest cost would be `3`.

But strategically, when `S` is playable, we want to play it immediately.

So instead we store it as `-1`.

Then the multiset sees:

- `-1, 3, 7`

and automatically picks `S` first.

When actually paying, we convert `-1` back to its real cost.

So the code is basically saying:

> "Treat the special card as the highest priority whenever it is available."

---

# Small Example

Suppose:

```text
n = 5, k = 2, p = 2, m = 7
costs = [4, 3, 2, 1, 5]
```

The special card is at position 2, so its cost is `3`.

We store it as `-1`.

Initial split:

- `hand = {4, -1}`
- `others = [2, 1, 5]`
- `realCost = 3`

### First move

Smallest in `hand` is `-1`, so play the special card.

- budget used = `3`
- answer = `1`

Move it to back:

- `others.push(-1)`
- move front of `others` (`2`) into `hand`

Now:

- `hand = {2, 4}`
- `others = [1, 5, -1]`

### Second move

Smallest in `hand` is `2`.

- budget used = `5`

Move it:

- `hand = {1, 4}`
- `others = [5, -1, 2]`

### Third move

Smallest is `1`.

- budget used = `6`

Move it:

- `hand = {4, 5}`
- `others = [-1, 2, 1]`

### Fourth move

Smallest is `4`, but `6 + 4 > 7`, so stop.

Final answer = `1`.

---

# Time Complexity

Let `k` be the number of playable cards.

Each loop iteration does:

- get smallest from multiset: `O(1)` for `begin()`
- erase from multiset: `O(log k)`
- insert into multiset: `O(log k)`
- queue push/pop: `O(1)`

So each turn costs:

```text
O(log k)
```

If the process runs for `T` turns, total is:

```text
O(T log k)
```

In practice, this is efficient enough for the constraints.

Space complexity is:

```text
O(n)
```

because all cards are stored in either `hand` or `others`.

---

# Final Summary

The solution works by:

1. representing the first `k` playable cards with a multiset
2. representing the rest of the deck with a queue
3. marking the special card as `-1`
4. repeatedly choosing the smallest playable card
5. paying for it if possible
6. counting it if it is the special card
7. moving it to the back and bringing the next card into the playable set

So the main idea is:

> Greedy choice + efficient simulation of the queue.

---

# Code

```cpp
#include <bits/stdc++.h>
using namespace std;

void solve()
{
    int n, k, p, m;
    cin >> n >> k >> p >> m; p--;

    multiset<int> hand;
    queue<int> others;
    
    int realCost = 0;
    for (int i = 0; i < n; i++) {
        int cur; cin >> cur;
        if (p == i) {
            realCost = cur;
            if (i < k) {
                hand.insert(-1);
            }
            else {
                others.push(-1);
            }
        }
        else {
            if (i < k) {
                hand.insert(cur);
            }
            else {
                others.push(cur);
            }
        }
    }

    int counter = 0;
    int totalPrice = 0;
    while (true) {
        int front = *(hand.begin());
        hand.erase(hand.begin());
        if (front == -1) {
            if (totalPrice + realCost > m) {
                break;
            }
            counter++;
            totalPrice += realCost;
        }
        else {
            if (totalPrice + front > m) {
                break;
            }
            totalPrice += front;
        }
        others.push(front);
        hand.insert(others.front());
        others.pop();
    }
    cout << counter << '\n';
}

int main()
{
    int t;
    cin >> t;
    while (t--) {
        solve();
    }
}
```