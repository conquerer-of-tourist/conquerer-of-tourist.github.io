# Problem A - A Simple Sequence

Writer:
* Hengsheng Wang
* Handle: `hangboy`

Dates:
* Creation: March 29th, 2026
* Last Update: March 29th, 2026

## Thought Process
### Reading The Question
There is some terminology in the question that may look complicated, but the idea is very simple.
* You just need to find an ordering of the values $1, 2, 3, ... n - 1, n$
* Such that:
    * $k_1 = \text{value 1 modulo value 2}$
    * $k_2 = \text{value 2 modulo value 3}$
    * $k_3 = \text{value 3 modulo value 4}$
    * And so on...
* At the end, you simply need $k_1 \ge k_2 \ge k_3 \dots$

### Test Cases
The provided cases are sufficient to get your brain going. An important note to keep in mind is, the problem setters are usually going to try to derail your brain.
* Looking at the outputs
    * `2 1` (2 modulo 1 is 1)
    * `2 3 1` (2 modulo 3 is 2, 3 modulo 1 is 1)
    * `2 4 3 1` (2 mod 4 is 2, 4 mod 3 is 1, 3 mod 1 is 0)
* These don't really follow a simple pattern

### Constructions

Then, it is probably useful to try out some constructions.

## Solution Explanation
After a bit of tinkering, you may find that for any $n \in \mathbb{Z}^+$:
* If $n = 2$, $2 \pmod 1 \equiv 0$
* Otherwise, $n \pmod{n - 1} \equiv 1$

Thus the output construction could just be
* $n, n - 1, n - 2, \cdots, 3, 2, 1$
* This way the modulo values are $1, 1, 1, \cdots, 1, 1, 0$

This approach yields AC.

## Program

```cpp

#include <bits/stdc++.h>
using namespace std;

void solve()
{
    int n;
    cin >> n;
    for (int i = n; i >= 1; i--) {
        if (i == 1) {
            cout << i << '\n';
            return;
        }
        cout << i << " ";
    }
    return;
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