# Problem C1 - A Simple GCD Problem
**Note:** This is the easy version of the problem.

Writer:
* Hengsheng Wang
* Handle: `hangboy`

Dates:
* Creation: March 29th, 2026
* Last Update: March 29th, 2026

## Thought Process
### Reading the question
Again, reading these problems is generally quite annoying. So this is the statement, but simplified:
* (We'll ignore array `b` for now, since it irrelevant in this version of the problem)
* You're given an array `a` of positive integers
* For each (`ith`) element in the array, you can set `a[i] = m`
    * Where $m < \texttt{a[i]}$.
    * The resulting array after changes will be called $a'$.
* However, you need to make sure, for any subarray from indices $l$ to $r$, inclusive, 
$$  \gcd(\texttt{a'[l]} \dots \texttt{a'[r]}) = 
    \gcd(\texttt{a[l]}  \dots \texttt{a[r]})$$

### Sample tests
Sample Test 1 is very trivial, and the statement explains it pretty well. The second test is also quite intuitive, because all values are the same. If one is changed, the whole GCD changes.

No explanation if provided for the third test case, but I think this one is the most thought-provoking, and it gives the most insight into solving the question.

```
INPUT                   OUTPUT
=====================================
6                       2
8 10 10 12 12 14
8 10 10 12 12 14
```

Why is the answer to this case equal to `2` ?
* Notice the 10's and 12's in the middle.
* If you change a 10 to some other value, like 8,
    * the GCD for `{10, 8}` becomes 2.
    * the GCD for `{12, 8}` becomes 4
    * and everything seems like it got messed up.

So, it's important to realize the we don't need to consider every subarray.
* The value $\gcd(\texttt{a[l] \dots \texttt{a[r]}})$ is equal to...
$$\gcd(
\gcd(\text{l, l + 1}), 
\gcd(\text{l + 1, l + 2}),
\dots,
\gcd(\text{r - 1, r})
)$$
* Because of this, we only have to consider pairwise GCD's.
* Let's say we have a triple `{a - 1, a, a + 1}`
    * These values are $28, 32, 40$.
    * The GCD is 4.
    * However, changing the 32 to a value such as 4 will keep the GCD as 4 for both pairs;
    * Hence the GCD of the entire subarray remains the same.

## Solution Explanation
The technique itself is mainly just number theory, but the solution itself is pretty intuitive.

The numbers on the sides are special cases, so we deal with them first.
* There is symmetry between first and last, so WLOG we consider the first one
* If there exists a smaller value such that the GCD remains the same
    * (the current value is more than the GCD),
    * We add to the answer
* The same applies to the second-to-last and last values.

For values in the middle,
* If the value is greater than the LCM of the GCD of surrounding pairwise terms, you can increment
* No proof here, but you can try it out with some simple cases.

## Program
```cpp
#include <bits/stdc++.h>
using namespace std;

void solve()
{
    int n;
    cin >> n;
    vector<int> a (n);
    vector<int> b (n);
    for (auto& k : a) cin >> k;
    for (auto& k : b) cin >> k;
    int counter = 0;
    if (a[0] > gcd(a[0], a[1])) {
        counter++;
    }
    if (a[n - 1] > gcd(a[n - 1], a[n - 2])) {
        counter++;
    }
    for (int i = 1; i < n - 1; i++) {
        if (a[i] > lcm(gcd(a[i], a[i - 1]), gcd(a[i], a[i + 1]))) {
            counter++;
        }
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
