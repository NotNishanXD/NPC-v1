# NPC v1

A powerful and lightweight NPC plugin for PocketMine-MP that allows server administrators to create interactive entities with dynamic text and command execution.

## Features

- **Interactive NPCs:** Execute commands when players click on NPCs.
- **Dynamic Text Placeholders:** Real-time updates for:
    - `{o}` - Online players count.
    - `{tps}` - Server Ticks Per Second.
    - `{c}` - Players in the NPC's current world.
    - `{date}` - Current date (DD/MM/YYYY).
    - `{hh:mm:ss}` - Current time.
    - `{top1}`, `{top2}`, `{top3}` - Top 3 richest players (Requires EconomyAPI).
- **Multiple NPC Types:**
    - `Human`: Player-like entity with custom skins.
    - `Floating`: Invisible entity with floating text and items.
    - `Text`: Multi-line floating text displays.
- **Packet-Based:** Efficient entity handling using custom packets.
- **NBT Persistence:** NPC data is stored in the entity's NBT, surviving server restarts.

## Requirements

- **PocketMine-MP:** API 2.0.0
- **EconomyAPI:** (Optional, required for Top Money placeholders)

## Installation

1. Download the plugin (In the releases).
2. Place the `NPC-v1.phar` (or the plugin folder) into your server's `plugins/` directory.
3. Restart the server.

## Commands

- `/npc add <human|floating|text> <world> <text...>`: Spawn an NPC at your location.
- `/npc addcmd <command>`: Click an NPC to assign a command to it.
- `/npc list-entitys`: List available NPC types.
- `/sudo <player> <command>`: Force a player to run a command.

## How to Remove an NPC

1. Hold a **Bone (ID 352)** in your hand.
2. Ensure you are an **OP**.
3. Click on the NPC you wish to remove.

## Credits

- **Original Author:** D4yv1d
- **Translation by:** NotNishanXD

---
*Note: This plugin uses `{player}` as a placeholder in commands to target the interacting player.*
