[![](https://poggit.pmmp.io/shield.state/HammerACM)](https://poggit.pmmp.io/p/HammerACM)

[![](https://poggit.pmmp.io/shield.api/HammerACM)](https://poggit.pmmp.io/p/HammerACM)

# HammerACM
**A simple 3x3 pickaxe, for PocketMine-MP 5.0 servers**

![icon-deviceacm](https://github.com/fernanACM/HammerACM/assets/83558341/18d63017-941e-4101-b0db-2583dced3ba4)

<a href="https://discord.gg/YyE9XFckqb"><img src="https://img.shields.io/discord/837701868649709568?label=discord&color=7289DA&logo=discord" alt="Discord" /></a>

### 🌍 Wiki
* Check our plugin [wiki](https://github.com/fernanACM/HammerACM/wiki) for features and secrets in the...

### 💡 Implementations
* [X] Configuration
* [X] Hammer per worlds

### 💾 Config 
```yaml
#   _   _                                                    _       ____   __  __ 
#  | | | |   __ _   _ __ ___    _ __ ___     ___   _ __     / \     / ___| |  \/  |
#  | |_| |  / _` | | '_ ` _ \  | '_ ` _ \   / _ \ | '__|   / _ \   | |     | |\/| |
#  |  _  | | (_| | | | | | | | | | | | | | |  __/ | |     / ___ \  | |___  | |  | |
#  |_| |_|  \__,_| |_| |_| |_| |_| |_| |_|  \___| |_|    /_/   \_\  \____| |_|  |_|
#               by fernanACM
# A simple hammer that breaks 3x3. For PocketMine-MP 5.0 servers only

# DO NOT TOUCH!
config-version: "1.0.0"

# Prefix plugin
Prefix: "&l[&bHammerACM&f]&8»&r "

# ==(CONFIGURATION)==
Settings:
  # ==(WORLD MANAGER)==
  # Enable and disable the hammer for worlds you 
  # add to "whitelist" or "blacklist" modes
  WorldManager:
    # Valid modes:
    # - whitelist
    # - blacklist
    mode: whitelist
    # Add the names of worlds that are in the whitelist
    worlds-whitelist:
      - "world"
      - "world-2"
      - "ACM"
    # Add the names of worlds that are in the blacklist
    worlds-blacklist:
      - "MinePvP"
      - "ZonePvP"

# ==(HAMMER)==
# Use this for color '&'
Hammer:
  item-name: "&r&l&bHammerACM"
  item-lore: "&r&cby: fernanACM"

Messages:
  success:
    successfully-delivered: "&aThe Hammer has been successfully delivered!"
  error:
    inventory-full: "&cYour inventory is full!"
```

### 🕹 Commands
| Command | Description |
|---------|-------------|
| ```/hammeracm``` | Receive hammer |

### 🔒 Permissions
| Permission | Description |
|---------|-------------|
| ```hammeracm.cmd:``` | Executing the command |
| ```hammeracm.use``` | Use hammer |

### 📢 Report bug
* If you find any bugs in this plugin, please let me know via: [issues](https://github.com/fernanACM/HammerACM/issues)

### 📞 Contact
| Redes | Tag | Link |
|-------|-------------|------|
| YouTube | fernanACM | [YouTube](https://www.youtube.com/channel/UC-M5iTrCItYQBg5GMuX5ySw) | 
| Discord | fernanACM#5078 | [Discord](https://discord.gg/YyE9XFckqb) |
| GitHub | fernanACM | [GitHub](https://github.com/fernanACM)
| Poggit | fernanACM | [Poggit](https://poggit.pmmp.io/ci/fernanACM)
****

### ✔ Credits
| Authors | Github | Lib |
|---------|--------|-----|
| DaPigGuy | [DaPigGuy](https://github.com/DaPigGuy) | [libPiggyUpdateChecker](https://github.com/DaPigGuy/libPiggyUpdateChecker) |
****
