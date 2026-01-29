import json
import sys

pace, shooting, passing, dribbling, defending, physical = map(float, sys.argv[1:])

formations = {
    "4-3-3": (
        0.35 * pace +
        0.30 * shooting +
        0.20 * dribbling +
        0.15 * passing
    ),
    "4-4-2": (
        0.30 * passing +
        0.25 * physical +
        0.25 * defending +
        0.20 * pace
    ),
    "4-2-3-1": (
        0.30 * passing +
        0.30 * dribbling +
        0.25 * shooting +
        0.15 * physical
    ),
    "3-5-2": (
        0.35 * passing +
        0.30 * physical +
        0.20 * defending +
        0.15 * dribbling
    ),
    "5-4-1": (
        0.40 * defending +
        0.35 * physical +
        0.25 * passing
    )
}

# normalisasi jadi probabilitas
total = sum(formations.values())

results = [
    {
        "formasi": k,
        "probability": round(v / total, 4)
    }
    for k, v in formations.items()
]

# ranking
results.sort(key=lambda x: x["probability"], reverse=True)

print(json.dumps(results))
